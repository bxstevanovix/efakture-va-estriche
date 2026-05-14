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
                return $entity-> debt . ' ' . $entity->currency . '€';
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
        
        $data = $request->validate([
            'id_invoice' => ['required', 'string', 'max:190'],
            'company' => ['required', 'integer', 'exists:firme,id'],
            'date_start' => ['required', 'string', 'date_format:d-m-Y'],
            'date_end' => ['required', 'string', 'date_format:d-m-Y', 'after_or_equal:date_start'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'text' => ['nullable', 'string', 'max:900'],
            'address' => ['nullable', 'string', 'max:900']

        ]);
        $data['date_start'] = date('Y-m-d',strtotime(request()->date_start));
        $data['date_end'] = date('Y-m-d',strtotime(request()->date_end));
        $entity = new Entity();
        
        $entity->fill($data);

        $entity->save();
        
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
        
        $data = $request->validate([
            'id_invoice' => ['required', 'string', 'max:190'],
            'company' => ['required', 'integer', 'exists:firme,id'],
            'date_start' => ['required', 'string', 'date_format:d-m-Y'],
            'date_end' => ['required', 'string', 'date_format:d-m-Y', 'after_or_equal:date_start'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'text' => ['nullable', 'string', 'max:900'],
            'address' => ['nullable', 'string', 'max:900']
        ]);

        $data['date_start'] = date('Y-m-d',strtotime(request()->date_start));
        $data['date_end'] = date('Y-m-d',strtotime(request()->date_end));
        
        $entity->fill($data);
        
        $entity->save();

        return redirect()->route('customer-invoices.index')->with('success', __('Faktura je uspešno izmenjena!'));
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
        $pdfPath = $this->normalizePublicPdfPath($faktura->pdf);
        $path = Storage::disk('public')->path($pdfPath);
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
        $pdfPath = $this->normalizePublicPdfPath($faktura->pdf);
        $path = Storage::disk('public')->path($pdfPath);
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
        $path = ltrim((string) $path, '/');

        foreach (['storage/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
            }
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
                if ($entity->price_part > 0) {
                    return $entity->price . ' (<span class="price-part-green">' . $entity->price_part . '</span>) ' . $entity->currency;
                } else {
                    return $entity->price . ' ' . $entity->currency;
                }
                
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
