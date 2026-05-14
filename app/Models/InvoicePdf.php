<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CustomerInvoice;
use App\Models\SupplierInvoice;

class InvoicePdf extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'invoice_type',
        'file_name',
        'file_path',
        'deleted_by',
    ];

    /**
     * Dohvata vezanu fakturu (customer ili supplier)
     */
    public function invoice()
    {
        if ($this->invoice_type === 'customer') {
            return $this->belongsTo(CustomerInvoice::class, 'invoice_id');
        }

        if ($this->invoice_type === 'supplier') {
            return $this->belongsTo(SupplierInvoice::class, 'invoice_id');
        }

        return null;
    }
}