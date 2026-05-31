<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Rechnung as Entity;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Firma;
use App\Models\Beschreibung;
use App\Models\CustomerInvoice;
use App\Services\DocxAngebotService;
use App\Services\LibreOfficePdfConverter;
use Illuminate\Validation\Rule;
use Throwable;

class RechnungController extends Controller 
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {   
        $firme = Firma::orderBy('name')->get();
        $companies = $firme;
        $customerInvoiceEntity = new CustomerInvoice();

        return view('make_rechnung.index', compact('firme', 'companies', 'customerInvoiceEntity'));
    }
    
    /*
     * Yajrabox datatables metod
     */
    public function datatable()
    {
        $query = Entity::query()->where('id', '>', 0);

        return datatables($query)
            ->editColumn('id_invoice', function ($entity) {
                $typeMap = [
                    'rechnung' => 'R',
                    'teilrechnung' => 'T',
                    'schlussrechnung' => 'S'
                ];

                return ($typeMap[$entity->type] ?? 'R') . ' - ' . $entity->id_invoice;
            })

            ->addColumn('actions', function ($entity) {
                $hasCustomerInvoice = CustomerInvoice::where('id_invoice', $entity->id_invoice)->exists();

                return view('make_rechnung.partials.table.actions', 
                            ['entity' => $entity, 'hasCustomerInvoice' => $hasCustomerInvoice]);
            })
            ->editColumn('price', function ($entity) {
                return '€ ' . $entity->formatted_price;
            })
            ->editColumn("date_start", function ($entity) {
                return $entity->date_start ? $entity->date_start->format("d.m.Y") : '-';
            })
            ->editColumn("created_by", function ($entity) {
                $user = User::find($entity->created_by);
                return $user ? $user->name : '-';
            })
            ->orderColumn('id_invoice', function ($query, $order) {
                $query->orderBy('id', $order); // 👈 SORT PO ID
            })
            ->rawColumns(['actions', 'image'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);
    }
    
    public function save(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:rechnung,teilrechnung,schlussrechnung',
            'customer_name' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'ort' => 'nullable|string|max:255',
            'uid' => 'nullable|string|max:50',
            'date' => 'required|date',
            'bvh' => 'nullable|string|max:255',
            'auftragsnr' => 'nullable|string|max:255',
            'rechnung_nr' => [
                'required',
                'string',
                'max:20',
                Rule::unique('rechnungen', 'id_invoice')->whereNull('deleted_at'),
            ],
            'ausführungszeit' => 'nullable|string|max:100',
            'invoice_note' => 'nullable|string',
            'total' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable',
            'deckungsrucklass_percent' => 'nullable|numeric|min:0|max:100',
            'use_tax' => 'nullable|boolean',
            'abzug_tr1' => 'nullable',
            'abzug_tr_label' => 'nullable|string|max:40',
            'spacing_top' => 'nullable|integer|min:0|max:160',
            'bank_details' => ['nullable', 'string', 'max:255', Rule::in($this->allowedBankDetails())],
            'items' => 'nullable|array',
            'items.*.name' => 'nullable|string|max:255',
            'items.*.qty' => 'nullable',
            'items.*.price' => 'nullable',
            'items.*.total' => 'nullable',
        ]
        , [
            'rechnung_nr.unique' => 'Rechnungsnummer bereits vergeben!'
        ]);

        $type = $data['type'];
        $customerName = trim($data['customer_name']);
        $adress = $data['adress'];
        $ort = $data['ort'] ?? null;
        $uid = $data['uid'] ?? null;
        $dateValue = $data['date'];
        $bvh = $data['bvh'] ?? null;
        $auftragsnr = $data['auftragsnr'] ?? null;
        $rechnungNr = $data['rechnung_nr'] ?? null;
        $ausführungszeit = $data['ausführungszeit'] ?? null;
        $invoiceNote = $data['invoice_note'] ?? null;
        $items = $this->normalizeItems($data['items'] ?? []);
        $totalValue = $items
            ? $this->calculateTotal($items, $data)
            : $this->parseMoney($data['total']);

        $folder = $this->folderForType($type);
        $storagePath = null;

        try {
            Storage::disk('public')->makeDirectory($folder);

            $slug = Str::slug($customerName);
            $number = Str::slug(str_replace('/', '-', $rechnungNr));
            $timestamp = Carbon::now()->format('dm-Hi');

            $filename = $this->getUniqueFileName($folder, "{$number}-{$slug}-{$timestamp}.pdf");
            $pdfBinary = $this->generateRechnungPdfBinary(
                $data,
                $items,
                $totalValue,
                $rechnungNr,
                $filename
            );

            $storagePath = $folder . '/' . $filename;
            Storage::disk('public')->put($storagePath, $pdfBinary);

            if (!Storage::disk('public')->exists($storagePath)) {
                throw new \RuntimeException('PDF saving failed.');
            }

            $invoice = DB::transaction(function () use (
                $type,
                $rechnungNr,
                $totalValue,
                $customerName,
                $adress,
                $ort,
                $uid,
                $bvh,
                $auftragsnr,
                $ausführungszeit,
                $invoiceNote,
                $dateValue,
                $storagePath,
                $items
            ) {
                $invoice = Entity::create([
                    'type' => $type,
                    'id_invoice' => $rechnungNr,
                    'price' => number_format($totalValue, 2, '.', ''),
                    'firma' => $customerName,
                    'adress' => $adress,
                    'ort' => $ort,
                    'uid' => $uid,
                    'bvh' => $bvh,
                    'auftragsnr' => $auftragsnr,
                    'ausfuhrungszeit' => $ausführungszeit,
                    'note' => $invoiceNote,
                    'date_start' => $dateValue,
                    'invoice_url' => $storagePath,
                    'created_by' => auth()->id(),
                ]);

                foreach ($items as $item) {
                    Beschreibung::create([
                        'invoice_type' => $type,
                        'invoice_id' => $invoice->id,
                        'name' => $item['name'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                    ]);
                }

                return $invoice;
            });

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'pdf_url' => route('rechnung.view', $invoice->id)
            ]);
        } catch (Throwable $exception) {
            if ($storagePath && Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }

            Log::error('Rechnung creation failed.', [
                'rechnung_number' => $rechnungNr,
                'type' => $type,
                'storage_path' => $storagePath,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => $this->userFacingRechnungError($exception),
            ], 500);
        }
    }

    public function viewPdf($id)
    {
        $rechnung = Entity::findOrFail($id);

        $filename = $rechnung->type . '-' . Str::slug(str_replace('/', '-', $rechnung->id_invoice), '-') . '.pdf';

        $pdfPath = $this->normalizePublicPdfPath($rechnung->invoice_url);
        $path = Storage::disk('public')->path($pdfPath);

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        if ($this->request->boolean('download')) {
            return response()->download($path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        if (! $this->request->boolean('raw')) {
            return view('pdf.viewer', [
                'title' => __('Rechnung') . ' ' . $rechnung->id_invoice,
                'fileName' => $filename,
                'pdfUrl' => route('rechnung.pdf', $rechnung->id),
                'downloadUrl' => $this->request->fullUrlWithQuery(['download' => 1]),
            ]);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function customerInvoiceData(Entity $entity)
    {
        return response()->json([
            'id' => $entity->id,
            'id_invoice' => $entity->id_invoice,
            'company' => $this->matchingFirmaId($entity->firma),
            'company_name' => $entity->firma,
            'address' => $this->customerInvoiceAddress($entity),
            'date_start' => $this->formatCustomerInvoiceDate($entity->date_start),
            'date_end' => '',
            'price' => number_format((float) $entity->price, 2, ',', ''),
            'text' => $this->plainTextFromHtml($entity->note),
            'pdf_available' => filled($entity->invoice_url)
                && Storage::disk('public')->exists($this->normalizePublicPdfPath($entity->invoice_url)),
        ]);
    }

    public function pdfFile($id)
    {
        $rechnung = Entity::findOrFail($id);
        $pdfPath = $this->normalizePublicPdfPath($rechnung->invoice_url);
        $path = Storage::disk('public')->path($pdfPath);
        $filename = $rechnung->type . '-' . Str::slug(str_replace('/', '-', $rechnung->id_invoice), '-') . '.pdf';

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    private function normalizePublicPdfPath(?string $path): string
    {
        $path = ltrim((string) $path, '/');

        foreach (['storage/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
            }
        }

        return $path;
    }

    private function matchingFirmaId(?string $firmaName): ?int
    {
        $firmaName = trim((string) $firmaName);

        if ($firmaName === '') {
            return null;
        }

        $firma = Firma::where('name', $firmaName)->first()
            ?? Firma::whereRaw('LOWER(TRIM(name)) = ?', [Str::lower($firmaName)])->first();

        return $firma?->id;
    }

    private function customerInvoiceAddress(Entity $rechnung): string
    {
        if (filled($rechnung->bvh)) {
            return trim((string) $rechnung->bvh);
        }

        return collect([$rechnung->adress, $rechnung->ort])
            ->filter(fn ($value) => filled($value))
            ->implode(', ');
    }

    private function formatCustomerInvoiceDate($date): string
    {
        if (! $date) {
            return '';
        }

        return Carbon::parse($date)->format('d-m-Y');
    }

    private function plainTextFromHtml(?string $value): string
    {
        $value = (string) $value;
        $value = preg_replace('/<\s*br\s*\/?>/i', "\n", $value);
        $value = preg_replace('/<\/\s*p\s*>/i', "\n", $value);

        return trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
    
    public function delete(Entity $entity)
    {
        $entity->delete();

        return response()->json([
            'success' => true,
            'message' => __('Racun je uspešno obrisan!')
        ]);
    }

    /**
     * Funkcija koja dodaje sufiks ako fajl već postoji u storage
     */
    protected function getUniqueFileName($folder, $filename)
    {
        $disk = 'public';
        $original = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $counter = 1;
        $path = "{$folder}/{$filename}";

        while (Storage::disk($disk)->exists($path)) {
            $filename = $original . '-' . $counter . '.' . $extension;
            $path = "{$folder}/{$filename}";
            $counter++;
        }

        return $filename;
    }

    private function folderForType(string $type): string
    {
        return [
            'rechnung' => 'rechnungen',
            'teilrechnung' => 'teilrechnungen',
            'schlussrechnung' => 'schlussrechnungen',
        ][$type] ?? 'rechnungen';
    }

    private function labelForType(string $type): string
    {
        return [
            'rechnung' => 'Rechnung',
            'teilrechnung' => 'Teilrechnung',
            'schlussrechnung' => 'Schlussrechnung',
        ][$type] ?? 'Rechnung';
    }

    private function normalizeItems(array $items): array
    {
        return collect($items)
            ->map(function ($item) {
                $name = trim((string) ($item['name'] ?? ''));
                $qty = trim((string) ($item['qty'] ?? ''));
                $price = trim((string) ($item['price'] ?? ''));
                $total = trim((string) ($item['total'] ?? ''));

                return [
                    'name' => $name,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => number_format($this->parseMoney($total), 2, '.', ''),
                ];
            })
            ->filter(function ($item) {
                return $item['name'] !== ''
                    || ($item['qty'] !== '' && $this->parseMoney($item['qty']) !== 0.0)
                    || $this->parseMoney($item['price']) > 0
                    || $this->parseMoney($item['total']) > 0;
            })
            ->values()
            ->all();
    }

    private function calculateTotal(array $items, array $data): float
    {
        $subtotal = collect($items)->sum(fn ($item) => $this->parseMoney($item['total'] ?? $item[3] ?? 0));

        $discountPercent = (float) ($data['discount_percent'] ?? 0);
        $discountFixed = $this->parseMoney($data['discount_fixed'] ?? 0);
        $deckungsrucklassPercent = (float) ($data['deckungsrucklass_percent'] ?? 0);
        $abzugTr1 = $this->parseMoney($data['abzug_tr1'] ?? 0);
        $useTax = filter_var($data['use_tax'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($discountPercent > 0) {
            $subtotal -= $subtotal * ($discountPercent / 100);
        }

        if ($discountFixed > 0) {
            $subtotal -= $discountFixed;
        }

        if ($deckungsrucklassPercent > 0) {
            $subtotal -= $subtotal * ($deckungsrucklassPercent / 100);
        }

        if ($useTax) {
            $subtotal += $subtotal * 0.20;
        }

        if ($abzugTr1 > 0) {
            $subtotal -= $abzugTr1;
        }

        return round(max($subtotal, 0), 2);
    }

    private function parseMoney($value): float
    {
        $value = trim((string) $value);
        $value = str_replace(['€', ' '], '', $value);

        if ($value === '') {
            return 0.0;
        }

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }

    private function generateRechnungPdfBinary(
        array $data,
        array $items,
        float $totalValue,
        string $rechnungNr,
        string $filename
    ): string {
        try {
            return $this->generateRechnungPdfBinaryFromDocx($data, $items, $totalValue, $rechnungNr, $filename);
        } catch (Throwable $exception) {
            Log::error('DOCX Rechnung PDF generation failed.', [
                'rechnung_number' => $rechnungNr,
                'type' => $data['type'] ?? null,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function generateRechnungPdfBinaryFromDocx(
        array $data,
        array $items,
        float $totalValue,
        string $rechnungNr,
        string $filename
    ): string {
        $workDir = storage_path('app/docx-rechnungen/' . Str::uuid());

        if (! is_dir($workDir)) {
            mkdir($workDir, 0775, true);
        }

        $docxName = pathinfo($filename, PATHINFO_FILENAME) . '.docx';
        $docxPath = $workDir . '/' . $docxName;
        $pdfPath = $workDir . '/' . pathinfo($docxName, PATHINFO_FILENAME) . '.pdf';
        $pdfConverter = app(LibreOfficePdfConverter::class);

        try {
            $docxData = $this->buildDocxRechnungData($data, $items, $totalValue, $rechnungNr, false);

            app(DocxAngebotService::class)->createFromData($docxPath, $docxData);
            $pdfConverter->convert($docxPath, $workDir, $pdfPath);
            $pdf = file_get_contents($pdfPath);

            if ($this->countPdfPages($pdf) > 1) {
                $docxData['show_page_numbers'] = true;
                app(DocxAngebotService::class)->createFromData($docxPath, $docxData);

                $pdfConverter->convert($docxPath, $workDir, $pdfPath);
                $pdf = file_get_contents($pdfPath);
            }
        } finally {
            $this->deleteDirectory($workDir);
        }

        return $pdf;
    }

    private function userFacingRechnungError(Throwable $exception): string
    {
        $message = $exception->getMessage();

        if (
            str_contains($message, 'LibreOffice')
            || str_contains($message, 'soffice')
            || str_contains($message, 'DOCX to PDF')
        ) {
            return 'PDF konnte nicht erstellt werden. Bitte prüfen, ob LibreOffice/soffice im Docker-Container installiert ist und SOFFICE_PATH korrekt gesetzt ist.';
        }

        if (str_contains($message, 'PDF saving failed')) {
            return 'PDF konnte nicht im Storage gespeichert werden. Bitte prüfen Sie die Storage-Berechtigungen.';
        }

        return 'Rechnung konnte nicht erstellt werden. Bitte versuchen Sie es erneut oder prüfen Sie die eingegebenen Daten.';
    }

    private function countPdfPages(string $pdf): int
    {
        $searchablePdf = $pdf . "\n" . $this->decodedPdfStreams($pdf);

        if (preg_match_all('/\/Type\s*\/Page(?!s)\b/', $searchablePdf, $matches) > 0) {
            return count($matches[0]);
        }

        if (preg_match_all('/\/Count\s+(\d+)/', $searchablePdf, $matches) > 0) {
            return max(array_map('intval', $matches[1]));
        }

        return 1;
    }

    private function decodedPdfStreams(string $pdf): string
    {
        if (! preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $pdf, $matches)) {
            return '';
        }

        $decoded = '';

        foreach ($matches[1] as $stream) {
            $inflated = @gzuncompress($stream);

            if ($inflated === false) {
                $inflated = @zlib_decode($stream);
            }

            if ($inflated === false) {
                $inflated = @gzinflate($stream);
            }

            if ($inflated !== false && strlen($inflated) <= 500000) {
                $decoded .= "\n" . $inflated;
            }
        }

        return $decoded;
    }

    private function buildDocxRechnungData(array $data, array $items, float $totalValue, string $rechnungNr, bool $showPageNumbers = false): array
    {
        $subtotal = collect($items)->sum(fn ($item) => $this->parseMoney($item['total'] ?? $item[3] ?? 0));
        $discountPercent = (float) ($data['discount_percent'] ?? 0);
        $discountFixed = $this->parseMoney($data['discount_fixed'] ?? 0);
        $deckungsPercent = (float) ($data['deckungsrucklass_percent'] ?? 0);
        $abzugTr1 = $this->parseMoney($data['abzug_tr1'] ?? 0);
        $useTax = filter_var($data['use_tax'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $adjustments = [];
        $running = $subtotal;

        if ($discountPercent > 0) {
            $amount = $running * ($discountPercent / 100);
            $running -= $amount;
            $adjustments[] = [
                'label' => '- ' . $this->formatNumber($discountPercent, 0) . '% Nachlass',
                'amount' => $this->formatMoney($amount),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($discountFixed > 0) {
            $running -= $discountFixed;
            $adjustments[] = [
                'label' => '- Pauschalenachlass',
                'amount' => $this->formatMoney($discountFixed),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($deckungsPercent > 0) {
            $amount = $running * ($deckungsPercent / 100);
            $running -= $amount;
            $adjustments[] = [
                'label' => '- ' . $this->formatNumber($deckungsPercent, 0) . '% Deckungsrücklass',
                'amount' => $this->formatMoney($amount),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($useTax) {
            $amount = $running * 0.20;
            $running += $amount;
            $adjustments[] = [
                'label' => '+ 20% MwSt',
                'amount' => $this->formatMoney($amount),
            ];
        }

        if ($abzugTr1 > 0) {
            $running -= $abzugTr1;
            $adjustments[] = [
                'label' => '- ' . $this->abzugTrLabel($data),
                'amount' => $this->formatMoney($abzugTr1),
                'running_total' => $this->formatMoney($running),
            ];
        }

        return [
            'document_label' => $this->labelForType((string) ($data['type'] ?? 'rechnung')),
            'customer_name' => trim((string) ($data['customer_name'] ?? '')),
            'address' => trim((string) ($data['adress'] ?? '')),
            'ort' => trim((string) ($data['ort'] ?? '')),
            'uid' => trim((string) ($data['uid'] ?? '')),
            'date' => Carbon::parse($data['date'])->format('d.m.Y'),
            'bvh' => trim((string) ($data['bvh'] ?? '')),
            'auftragsnr' => trim((string) ($data['auftragsnr'] ?? '')),
            'number' => $rechnungNr,
            'ausfuehrungszeit' => trim((string) ($data['ausführungszeit'] ?? '')),
            'spacing_top' => (int) ($data['spacing_top'] ?? 20),
            'use_tax' => $useTax,
            'show_page_numbers' => $showPageNumbers,
            'bank_details' => $this->bankDetails($data),
            'note_html' => (string) ($data['invoice_note'] ?? ''),
            'items' => collect($items)->map(fn ($item) => [
                $item['name'] ?? $item[0] ?? '',
                $item['qty'] ?? $item[1] ?? '',
                $item['price'] ?? $item[2] ?? '',
                $this->formatMoney($this->parseMoney($item['total'] ?? $item[3] ?? 0)),
            ])->all(),
            'summary' => [
                'subtotal' => $this->formatMoney($subtotal),
                'adjustments' => $adjustments,
                'total' => $this->formatMoney($totalValue),
            ],
        ];
    }

    private function abzugTrLabel(array $data): string
    {
        $label = trim((string) ($data['abzug_tr_label'] ?? ''));

        return $label !== '' ? $label : 'Abz. TR 1';
    }

    private function bankDetails(array $data): string
    {
        $bankDetails = trim((string) ($data['bank_details'] ?? ''));

        return in_array($bankDetails, $this->allowedBankDetails(), true)
            ? $bankDetails
            : $this->defaultBankDetails();
    }

    private function defaultBankDetails(): string
    {
        return 'Bankverbindung: UniCredit Bank Austria AG, BIC: BKAUATWW, IBAN: AT22 1200 0006 2226 3507';
    }

    private function allowedBankDetails(): array
    {
        return [
            $this->defaultBankDetails(),
            'Bankverbindung: Volksbank Niederösterreich AG, BIC: VBOEATWWNOM, IBAN: AT50 4715 0119 8151 0000',
            'Bankverbindung: Volksbank Niederösterreich AG, BIC: VBOEATWWNOM, IBAN: AT23 4715 0119 8151 0001',
        ];
    }

    private function formatMoney(float $value): string
    {
        return number_format(max($value, 0), 2, ',', '.');
    }

    private function formatNumber(float $value, int $digits = 2): string
    {
        return number_format($value, $digits, ',', '.');
    }

    private function deleteDirectory(string $directory): void
    {
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            if ($this->removeDirectory($directory)) {
                return;
            }

            usleep(100000 * $attempt);
        }

        Log::warning('Temporary DOCX Rechnung directory could not be deleted.', [
            'directory' => $directory,
        ]);
    }

    private function removeDirectory(string $directory): bool
    {
        if (! is_dir($directory)) {
            return true;
        }

        @chmod($directory, 0775);

        foreach (scandir($directory) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . '/' . $item;

            if (is_dir($path) && ! is_link($path)) {
                $this->removeDirectory($path);
            } else {
                @chmod($path, 0664);
                @unlink($path);
            }
        }

        return @rmdir($directory) || ! is_dir($directory);
    }

    public function autocompleteFirma(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $rechnungen = Entity::whereNotNull('firma')
            ->where('firma', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'firma', $q);
            })
            ->select('firma')
            ->distinct()
            ->limit(20)
            ->pluck('firma');

        $firme = Firma::whereNotNull('name')
            ->where('name', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'name', $q);
            })
            ->select('name')
            ->distinct()
            ->limit(20)
            ->pluck('name');

        $results = $this->prepareAutocompleteResults($rechnungen->merge($firme), $q);

        return $this->autocompleteResponse($results);
    }

    public function autocompleteAdress(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $rechnungen = Entity::whereNotNull('adress')
            ->where('adress', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'adress', $q);
            })
            ->select('adress')
            ->distinct()
            ->limit(20)
            ->pluck('adress');

        $firme = Firma::whereNotNull('address')
            ->where('address', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'address', $q);
            })
            ->select('address')
            ->distinct()
            ->limit(20)
            ->pluck('address');

        $results = $this->prepareAutocompleteResults($rechnungen->merge($firme), $q);

        return $this->autocompleteResponse($results);
    }

    public function autocompleteBeschreibung(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $results = Beschreibung::whereNotNull('name')
            ->where('name', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'name', $q);
            })
            ->select('name')
            ->distinct()
            ->limit(30)
            ->pluck('name');

        $results = $this->prepareAutocompleteResults($results, $q);

        return $this->autocompleteResponse($results);
    }

    private function autocompleteResponse($results)
    {
        return response()->json($results)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    private function applyAutocompleteSearch($query, string $column, string $term): void
    {
        $normalizedTerm = $this->normalizeAutocompleteTerm($term);
        $normalizedColumn = "REPLACE(REPLACE(REPLACE(REPLACE(LOWER({$column}), 'ä', 'ae'), 'ö', 'oe'), 'ü', 'ue'), 'ß', 'ss')";

        $query->where($column, 'LIKE', "%{$term}%")
            ->orWhereRaw("{$normalizedColumn} LIKE ?", ["%{$normalizedTerm}%"]);
    }

    private function prepareAutocompleteResults($values, string $term)
    {
        $normalizedTerm = $this->normalizeAutocompleteTerm($term);

        return collect($values)
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->filter(function ($value) use ($term, $normalizedTerm) {
                $value = (string) $value;

                return mb_stripos($value, $term) !== false
                    || str_contains($this->normalizeAutocompleteTerm($value), $normalizedTerm);
            })
            ->unique(fn ($value) => $this->normalizeAutocompleteTerm((string) $value))
            ->values()
            ->take(10);
    }

    private function normalizeAutocompleteTerm(string $value): string
    {
        $value = mb_strtolower(trim($value));

        return str_replace(
            ['ä', 'ö', 'ü', 'ß'],
            ['ae', 'oe', 'ue', 'ss'],
            $value
        );
    }
}
