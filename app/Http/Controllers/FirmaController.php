<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Firma as Entity;
use App\Models\CustomerInvoice;
use App\Models\SupplierInvoice;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class FirmaController extends Controller 
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;

    }
    
    public function index()
    {   
        return view('firme.index');
    }
    
    /*
     * Yajrabox datatables metod
     */
    public function datatable()
    {
        $query = Entity::query()->where('id', '>', 0)->orderBy('created_at', 'desc');

        return datatables($query)
            ->addColumn('actions', function ($entity) {
                return view('firme.partials.table.actions', 
                            ['entity' => $entity]);
            })
            ->editColumn('name', function ($entity) {
                return '<a href="' . route('firme.show', ['entity' => $entity->id]) . '" class="text-primary font-w600">' . e($entity->name) . '</a>';
            })
            ->editColumn('phone', function ($entity) {
                return $entity-> phone ? $entity->phone : '---';
            })
           
            ->rawColumns(['name', 'actions', 'image'])
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
        
        return view('firme.create', [
            'entity' => $entity,
        ]);
    }
    
    public function store()
    {   
        $request = $this->request;
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'address' => ['required', 'string', 'max:100'],
            'ort' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:firme,email'],
            'phone' => ['required', 'string', 'max:15'],
            'jib' => ['required', 'string', 'max:20'],
            'uid' => ['required', 'string', 'max:20'],
        ]);

        $entity = new Entity();
        
        $entity->fill($data);
        $entity->save();
        
        return redirect()->route('firme.index')->with('success', __('Firma je uspešno sačuvana!'));

    }

    public function show(Entity $entity)
    {
        $outgoingInvoiceCount = CustomerInvoice::where('company', $entity->id)->count();
        $incomingInvoiceCount = SupplierInvoice::where('company', $entity->id)->count();
        $outgoingPaidInvoiceCount = CustomerInvoice::where('company', $entity->id)->where('status', 1)->count();
        $incomingPaidInvoiceCount = SupplierInvoice::where('company', $entity->id)->where('status', 1)->count();
        $outgoingUnpaidInvoiceCount = CustomerInvoice::where('company', $entity->id)->where('status', 0)->count();
        $incomingUnpaidInvoiceCount = SupplierInvoice::where('company', $entity->id)->where('status', 0)->count();
        $outgoingOpenAmount = (float) CustomerInvoice::where('company', $entity->id)->where('status', 0)->sum('debt');
        $outgoingPaidAmount = (float) CustomerInvoice::where('company', $entity->id)->where('status', 1)->sum('price');
        $incomingOpenAmount = (float) SupplierInvoice::where('company', $entity->id)->where('status', 0)->sum('debt');
        $incomingPaidAmount = (float) SupplierInvoice::where('company', $entity->id)->where('status', 1)->sum('price');

        if ($outgoingInvoiceCount > 0 && $incomingInvoiceCount > 0) {
            $companyType = __('Kupac i dobavljač');
        } elseif ($outgoingInvoiceCount > 0) {
            $companyType = __('Kupac');
        } elseif ($incomingInvoiceCount > 0) {
            $companyType = __('Dobavljač');
        } else {
            $companyType = __('Bez faktura');
        }

        return view('firme.show', [
            'entity' => $entity,
            'companyType' => $companyType,
            'outgoingOpenAmount' => $outgoingOpenAmount,
            'outgoingPaidAmount' => $outgoingPaidAmount,
            'incomingOpenAmount' => $incomingOpenAmount,
            'incomingPaidAmount' => $incomingPaidAmount,
            'openBalance' => $outgoingOpenAmount - $incomingOpenAmount,
            'paidBalance' => $outgoingPaidAmount - $incomingPaidAmount,
            'totalPaidAmount' => $outgoingPaidAmount + $incomingPaidAmount,
            'totalOpenAmount' => $outgoingOpenAmount + $incomingOpenAmount,
            'outgoingInvoiceCount' => $outgoingInvoiceCount,
            'incomingInvoiceCount' => $incomingInvoiceCount,
            'paidInvoiceCount' => $outgoingPaidInvoiceCount + $incomingPaidInvoiceCount,
            'unpaidInvoiceCount' => $outgoingUnpaidInvoiceCount + $incomingUnpaidInvoiceCount,
        ]);
    }
    
    public function edit(Entity $entity)
    {
            return view('firme.edit', [
            'entity' => $entity,
        ]);
    }
    
    public function update(Entity $entity)
    {
        $request = $this->request;
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'address' => ['required', 'string', 'max:100'],
            'ort' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'string', 'max:15'],
            'jib' => ['required', 'string', 'max:20'],
            'uid' => ['required', 'string', 'max:20'],
            // 'currency' => ['required', 'string', 'max:3']
        ]);
        
        $entity->fill($data);
        $entity->save();
        
        return redirect()->route('firme.index')->with('success', __('Firma je uspešno izmenjena!'));
    }
    
    public function delete(Entity $entity)
    {
        $entity->delete();
        
        return response()->json([
            'success' => true,
            'message' => __('Firma je uspešno obrisana!'),
        ]);
    }

}
