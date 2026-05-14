<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CustomerInvoice;
use App\Models\Firma;
use App\Models\SupplierInvoice;

class DashController extends Controller
{
    public function index()
    {   
        // Brojanje faktura prema statusu
        $invoicesStatus1 = CustomerInvoice::where('status', 1)->count();
        $invoicesStatus0 = CustomerInvoice::where('status', 0)->count();
    
        // Sabiranje cene faktura prema statusu
        $invoicesPrice1 = CustomerInvoice::where('status', 1)->sum('price');
        $invoicesPrice0 = CustomerInvoice::where('status', 0)->sum('price');
    
        // Brojanje nabavki prema statusu
        $procurementStatus1 = SupplierInvoice::where('status', 1)->count();
        $procurementStatus0 = SupplierInvoice::where('status', 0)->count();
    
        // Sabiranje cene nabavki prema statusu
        $procurementPrice1 = SupplierInvoice::where('status', 1)->sum('price');
        $procurementPrice0 = SupplierInvoice::where('status', 0)->sum('price');

        // Aggregate data by month
        $monthlyInvoices = CustomerInvoice::selectRaw('MONTH(date_start) as month, SUM(price) as total')
            ->where('status', 1)
            ->whereYear('date_start', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyProcurements = SupplierInvoice::selectRaw('MONTH(date_start) as month, SUM(price) as total')
            ->where('status', 1)
            ->whereYear('date_start', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Aggregate data by month
        $monthlyInvoicesNotPaid = CustomerInvoice::selectRaw('MONTH(date_start) as month, SUM(price) as total')
            ->where('status', 0)
            ->whereYear('date_start', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyProcurementsNotPaid = SupplierInvoice::selectRaw('MONTH(date_start) as month, SUM(price) as total')
            ->where('status', 0)
            ->whereYear('date_start', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard', [
            'invoicesStatus1' => $invoicesStatus1,
            'invoicesStatus0' => $invoicesStatus0,
            'invoicesPrice1' => $invoicesPrice1,
            'invoicesPrice0' => $invoicesPrice0,
            'procurementStatus1' => $procurementStatus1,
            'procurementStatus0' => $procurementStatus0,
            'procurementPrice1' => $procurementPrice1,
            'procurementPrice0' => $procurementPrice0,
            'monthlyInvoices' => $monthlyInvoices,
            'monthlyProcurements' =>$monthlyProcurements,
            'monthlyInvoicesNotPaid' => $monthlyInvoicesNotPaid,
            'monthlyProcurementsNotPaid' => $monthlyProcurementsNotPaid,
        ]);
    }



    public function datatableCustomers(Request $request)
    {
        $dateStart = Carbon::parse($request->date_start)->format('Y-m-d');
        $query = CustomerInvoice::query()
            ->where('status', 0)
            ->whereDate('date_end', $dateStart)
            ->orderBy('created_at', 'desc');


        $datatables = datatables($query)
            ->editColumn('price', function ($entity) {
                if ($entity->price_part > 0) {
                    return $entity->price .' ' . $entity->currency . ' (<span class="price-part-green">' . $entity->price_part . ' uplaceno</span>) ';
                } else {
                    return $entity->price . ' ' . $entity->currency;
                }
                
            })->editColumn('company', function ($entity) {
                $company = Firma::where('id', $entity->company)->first();    
                return $company['name'];  
            })->rawColumns(['price'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);

        $datatables = $datatables->getData(true);

        return response()->json($datatables);
    } 
    public function datatableSuppliers(Request $request)
    {
        $dateStart = Carbon::parse($request->date_start1)->format('Y-m-d');
        $query = SupplierInvoice::query()
            ->where('status', 0)
            ->whereDate('date_end', $dateStart)
            ->orderBy('created_at', 'desc');


        $datatables = datatables($query)
            ->editColumn('price', function ($entity) {
                if ($entity->price_part > 0) {
                    return $entity->price .' ' . $entity->currency . ' (<span class="price-part-green">' . $entity->price_part . ' uplaceno</span>) ';
                } else {
                    return $entity->price . ' ' . $entity->currency;
                }
                
            })->editColumn('company', function ($entity) {
                $company = Firma::where('id', $entity->company)->first();    
                return $company['name'];  
            })->rawColumns(['price'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);

        $datatables = $datatables->getData(true);

        return response()->json($datatables);
    }
}
