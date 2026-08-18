<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;

use App\Models\CustomerInvoice as Entity;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Firma;
use App\Models\InvoicePdf;
use App\Models\Rechnung;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CustomerInvoicesController extends Controller 
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;

    }
    
    public function index()
    {   
        $sum = $this->getSummary();
        
        return view('customer_invoices.index', ['sum' => $sum]);
    }
    
    public function datatable()
    {
        $query = Entity::query()->where('id', '>', 0)->orderBy('created_at', 'desc');

        return datatables($query)
            ->addColumn('actions', function ($entity) {
                return view('customer_invoices.partials.table.actions', 
                            ['entity' => $entity]);
            })
            ->editColumn('debt', function ($entity) {
                return  '€ ' . $entity->price;
            })->editColumn('company', function ($entity) {
                $company = Firma::withTrashed()->where('id', $entity->company)->first();

                if (!$company) {
                    return '---';
                }

                return '<a href="' . route('firme.show', ['entity' => $company->id]) . '" class="">' . e($company->name) . '</a>';
            })->editColumn('paid', function ($entity) {
                return view('customer_invoices.partials.table.paid_actions', 
                            ['entity' => $entity]);
            })->editColumn('date_start',function($entity){
                $date = Carbon::parse($entity->date_start);
                return $date->format('d-m-Y');
            })->editColumn('date_end',function($entity){
                $date = Carbon::parse($entity->date_end);
                return $date->format('d-m-Y');
            })->orderColumn('date_end', function ($query, $order) {
                $direction = strtolower($order) === 'asc' ? 'asc' : 'desc';

                $query
                    ->reorder('status', 'asc')
                    ->orderBy('date_end', $direction)
                    ->orderBy('id', $direction);
            })->rawColumns(['actions', 'company', 'image', 'drivers', 'trucks'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);
    }
    
    public function create()
    {   
        $entity = new Entity();
        $companies = Firma::all();
        
        return view('customer_invoices.create', [
            'entity' => $entity,
            'companies' => $companies
        ]);
    }
    
    public function store()
    {   
        $request = $this->request;
        $this->normalizeMoneyInput($request, 'price');
        
        $data = $request->validate([
            'id_invoice' => [
                'required',
                'string',
                'max:190',
                Rule::unique('customer_invoices', 'id_invoice')->whereNull('deleted_at'),
            ],
            'company' => ['required', 'integer', 'exists:firme,id'],
            'date_start' => ['required', 'string', 'date_format:d-m-Y'],
            'date_end' => ['required', 'string', 'date_format:d-m-Y', 'after_or_equal:date_start'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'text' => ['nullable', 'string', 'max:900'],
            'address' => ['nullable', 'string', 'max:900'],
            'source_rechnung_id' => ['nullable', 'integer', 'exists:rechnungen,id'],

        ], [
            'id_invoice.unique' => __('Broj fakture već postoji u izlaznim fakturama!'),
        ]);

        $sourceRechnungId = $data['source_rechnung_id'] ?? null;
        unset($data['source_rechnung_id']);

        $data['date_start'] = date('Y-m-d',strtotime(request()->date_start));
        $data['date_end'] = date('Y-m-d',strtotime(request()->date_end));
        $entity = new Entity();
        
        $entity->fill($data);

        $entity->save();
        $this->copyRechnungPdfToCustomerInvoice($sourceRechnungId, $entity);
        
        return redirect()->route('customer-invoices.index')->with('success', __('Faktura je uspešno sačuvana!'));
    }
    public function edit(Entity $entity)
    {
        $companies = Firma::all();

        return view('customer_invoices.edit', [
            'entity' => $entity,
            'companies' => $companies,
        ]);
    }
    
    public function update(Entity $entity)
    {
        $request = $this->request;
        $this->normalizeMoneyInput($request, 'price');
        
        $data = $request->validate([
            'id_invoice' => [
                'required',
                'string',
                'max:190',
                Rule::unique('customer_invoices', 'id_invoice')->ignore($entity->id)->whereNull('deleted_at'),
            ],
            'company' => ['required', 'integer', 'exists:firme,id'],
            'date_start' => ['required', 'string', 'date_format:d-m-Y'],
            'date_end' => ['required', 'string', 'date_format:d-m-Y', 'after_or_equal:date_start'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'text' => ['nullable', 'string', 'max:900'],
            'address' => ['nullable', 'string', 'max:900']
        ], [
            'id_invoice.unique' => __('Broj fakture već postoji u izlaznim fakturama!'),
        ]);

        $data['date_start'] = date('Y-m-d',strtotime(request()->date_start));
        $data['date_end'] = date('Y-m-d',strtotime(request()->date_end));
        
        $entity->fill($data);
        
        $entity->save();

        return redirect()->route('customer-invoices.index')->with('success', __('Faktura je uspešno izmenjena!'));
    }

    private function normalizeMoneyInput(Request $request, string $field): void
    {
        if (!$request->has($field)) {
            return;
        }

        $value = trim((string) $request->input($field));

        if ($value === '') {
            return;
        }

        $value = preg_replace('/\s+/', '', $value);
        $lastComma = strrpos($value, ',');
        $lastDot = strrpos($value, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                $value = str_replace(',', '', $value);
            }
        } elseif ($lastComma !== false) {
            $value = str_replace(',', '.', $value);
        }

        $request->merge([$field => $value]);
    }
    
    public function delete(Entity $entity)
    {
        $entity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Račun je uspešno obrisan.'
        ]);
    }

    public function paid(Entity $entity)
    {
        request()->validate([
            'id' => 'required|exists:customer_invoices,id',
            'payment_date' => 'required|date'
        ]);

        $entity = Entity::findOrFail(request()->id);
        $paymentDate = \Carbon\Carbon::createFromFormat('d-m-Y', request()->payment_date)->format('Y-m-d');
        $entity->date_done = $paymentDate;
        $entity->status = 1;
        $entity->save();

        // Vrati JSON response za AJAX
        return response()->json([
            'success' => true,
            'message' => __('Die Zahlungsbestätigung wurde erfolgreich gespeichert!')
        ]);
    }

    public function getSummary()
    {
        // Izračunavanje ukupnog dugovanja za fakture sa statusom 0
        $pending = Entity::where('status', 0)->sum('debt');

        $response = [
            'pending' => [
                'eur' => $pending,
            ]
        ];

        return response()->json($response);
    }

    public function viewPdf($id)
    {
        $faktura = Entity::findOrFail($id);
        $path = $this->resolvePdfPath($faktura->pdf);
        $filename = 'Rechnung_' . Str::slug(str_replace('/', '-', $faktura->id_invoice), '-') . '.pdf';

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
                'title' => __('Rechnung') . ' ' . $faktura->id_invoice,
                'fileName' => $filename,
                'pdfUrl' => route('customer-invoices.pdf', $faktura->id),
                'downloadUrl' => $this->request->fullUrlWithQuery(['download' => 1]),
            ]);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function pdfFile($id)
    {
        $faktura = Entity::findOrFail($id);
        $path = $this->resolvePdfPath($faktura->pdf);
        $filename = 'Rechnung_' . Str::slug(str_replace('/', '-', $faktura->id_invoice), '-') . '.pdf';

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
        $path = rawurldecode(trim((string) $path));
        $path = str_replace('\\', '/', $path);

        // Stari zapisi mogu sadržati URL ili apsolutnu serversku putanju.
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $path = (string) parse_url($path, PHP_URL_PATH);
        }

        foreach (['/storage/app/public/', '/public/storage/'] as $marker) {
            if (($position = strpos($path, $marker)) !== false) {
                return ltrim(substr($path, $position + strlen($marker)), '/');
            }
        }

        $path = ltrim($path, '/');

        foreach (['storage/app/public/', 'public/storage/', 'storage/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return ltrim(substr($path, strlen($prefix)), '/');
            }
        }

        return $path;
    }

    private function resolvePdfPath(?string $path): string
    {
        $path = $this->normalizePublicPdfPath($path);

        // Legacy fakture su čuvane direktno u public/data direktoriju.
        if (str_starts_with($path, 'data/')) {
            return public_path($path);
        }

        return Storage::disk('public')->path($path);
    }

    private function copyRechnungPdfToCustomerInvoice(?int $rechnungId, Entity $invoice): void
    {
        if (! $rechnungId) {
            return;
        }

        $rechnung = Rechnung::find($rechnungId);

        if (! $rechnung || ! $rechnung->invoice_url || ! $invoice->firma) {
            return;
        }

        $sourcePath = $this->normalizePublicPdfPath($rechnung->invoice_url);

        if (! Storage::disk('public')->exists($sourcePath)) {
            return;
        }

        try {
            $companySlug = Str::slug($invoice->firma->name);
            $companyFolder = $invoice->firma->id . '-' . $companySlug;
            $invoiceFolder = str_replace(['/', ' '], '-', $invoice->id_invoice);
            $folder = 'ausgangsrechnungen/' . $companyFolder . '/' . $invoiceFolder;

            Storage::disk('public')->makeDirectory($folder);

            $sourceName = pathinfo($sourcePath, PATHINFO_FILENAME);
            $filename = (Str::slug($sourceName) ?: 'rechnung-' . $invoice->id) . '.pdf';
            $targetPath = $this->uniquePublicFilePath($folder, $filename);

            Storage::disk('public')->copy($sourcePath, $targetPath);

            $invoice->pdf = $targetPath;
            $invoice->save();
        } catch (\Throwable $exception) {
            Log::warning('Rechnung PDF could not be copied to customer invoice.', [
                'rechnung_id' => $rechnungId,
                'customer_invoice_id' => $invoice->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function uniquePublicFilePath(string $folder, string $filename): string
    {
        $original = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION) ?: 'pdf';
        $path = "{$folder}/{$filename}";
        $counter = 1;

        while (Storage::disk('public')->exists($path)) {
            $path = "{$folder}/{$original}-{$counter}.{$extension}";
            $counter++;
        }

        return $path;
    }

    public function uploadPdf(Request $request)
    {
        $request->validate([
            'entity_id' => 'required|exists:customer_invoices,id',
            'pdf_file' => 'required|file|mimes:pdf|max:102400'
        ]);

        $invoice = Entity::findOrFail($request->entity_id);
        $company = $invoice->firma;

        // folder firme
        $companySlug = Str::slug($company->name);
        $companyFolder = $company->id . '-' . $companySlug;

        // folder fakture
        $invoiceFolder = str_replace(['/', ' '], '-', $invoice->id_invoice);

        $folder = 'ausgangsrechnungen/' . $companyFolder . '/' . $invoiceFolder;

        // originalno ime fajla
        $originalName = $request->file('pdf_file')->getClientOriginalName();

        // opcionalno očisti ime (PREPORUKA)
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $cleanName = Str::slug($nameWithoutExtension);
        $filename = $cleanName . '.pdf';

        // snimanje
        $path = $request->file('pdf_file')->storeAs($folder, $filename, 'public');

        // snimi u bazu
        $invoice->pdf = $path;
        $invoice->save();

        return response()->json([
            'success' => true,
            'message' => __('PDF dokument je uspešno sačuvan!')
        ]);
    }

    public function uploadMorePdf(Request $request)
    {
        $request->validate([
            'entity_id' => 'required|integer', // id fakture
            'pdf_file' => 'required|file|mimes:pdf|max:102400'
        ]);

        
        $invoice = Entity::findOrFail($request->entity_id);
        
        $company = $invoice->firma;

        // folder firme
        $companySlug = Str::slug($company->name);
        $companyFolder = $company->id . '-' . $companySlug;

        // folder fakture
        $invoiceFolder = str_replace(['/', ' '], '-', $invoice->id_invoice);

        $folder = 'ausgangsrechnungen/' . $companyFolder . '/' . $invoiceFolder;

        // originalno ime fajla
        $originalName = $request->file('pdf_file')->getClientOriginalName();

        // opcionalno očisti ime (PREPORUKA)
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $cleanName = Str::slug($nameWithoutExtension);
        $filename = $cleanName . '.pdf';

        // snimanje fajla u storage/app/public
        $path = $request->file('pdf_file')->storeAs($folder, $filename, 'public');

        // snimanje u tabelu invoice_pdfs
        InvoicePdf::create([
            'invoice_id' => $invoice->id,
            'invoice_type' => 'customer',
            'file_name' => $filename,
            'file_path' => $path,
        ]);

        return back()->with('success', __('PDF dokument je uspešno sačuvan!'));

        // return response()->json([
        //     'success' => true,
        //     'message' => __('PDF dokument je uspešno sačuvan!')
        // ]);
    }

    public function deletePdf(Entity $entity)
    {
        // Ako postoji fajl u bazi i na disku
        if ($entity->pdf && Storage::disk('public')->exists($entity->pdf)) {
            Storage::disk('public')->delete($entity->pdf);
        }

        // Obriši iz baze
        $entity->pdf = null;
        $entity->save();

        return redirect()->back()->with('success', __('PDF je uspešno obrisan.'));
    }

    //////////////////////////////////////////////// IZVESTAJI //////////////////////////////////////////////////
    public function reports()
    {   
        $companies = Firma::all();

        return view('customer_invoices.reports.index', [
            'companies' => $companies
        ]);

    }

    public function reportsDatatable(Request $request)
    {
        $query = Entity::query()->where('id', '>', 0)->orderBy('created_at', 'desc');
        
        // Apply date range filter
        if ($request->has('date_start') && $request->date_start) {
            $date = \DateTime::createFromFormat('d-m-Y', $request->date_start);
            if ($date) {
                $formattedDate = $date->format('Y-m-d');
                $query->where('date_start', '>=', $formattedDate);
            } else {
                return response()->json(['error' => 'Invalid start date format'], 400);
            }
        }

        if ($request->has('date_end') && $request->date_end) {
            $date = \DateTime::createFromFormat('d-m-Y', $request->date_end);
            if ($date) {
                $formattedDate = $date->format('Y-m-d');
                $query->where('date_start', '<=', $formattedDate);
            } else {
                return response()->json(['error' => 'Invalid end date format'], 400);
            }
        }

        if ($request->has('company') && $request->company) {
            $query->where('company', $request->company);
        }

        if ($request->has('status') && isset($request->status)) {
            $query->where('status', $request->status);
        }
            
        $sumEUR = $this->calculateCurrencySums($query, 'EUR');

        $datatables = datatables($query)
            ->addColumn('actions', function ($entity) {
                return view('customer_invoices.reports.table.actions', 
                            ['entity' => $entity]);
            })
            ->editColumn('price', function ($entity) {
                    return  '€ ' . $entity->price;
            })->editColumn('company', function ($entity) {
                $company = Firma::where('id', $entity->company)->first();    
                return $company['name'];  
            
            })->editColumn('date_start',function($entity){
                $date = Carbon::parse($entity->date_start);
                return $date->format('d-m-Y');
            })->editColumn('date_end',function($entity){
                $date = Carbon::parse($entity->date_end);
                return $date->format('d-m-Y');
            })->rawColumns(['actions', 'price'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);

        $datatables = $datatables->getData(true);
        $datatables['sumEUR'] = $sumEUR;

        return response()->json($datatables);
    }

    public function calculateCurrencySums($query, $currency)
    {
        // Kopirajte originalni upit
        $queryCopy1 = clone $query;
        $queryCopy2 = clone $query;
        $queryCopy3 = clone $query;

        // Računanje vrednosti za određenu valutu
        $pending = $queryCopy1->where('status', 0)->sum('price');

        $paid = $queryCopy2->where('status', 1)->sum('price');

        
        return ['pending' => $pending, 'paid' => $paid];
    }

    public function autocompleteAddress(Request $request)
    {
        $q = $request->q;

        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Entity::where('address', 'LIKE', "%{$q}%")
            ->select('address')
            ->distinct()
            ->limit(10)
            ->pluck('address');

        return response()->json($results);
    }
}
