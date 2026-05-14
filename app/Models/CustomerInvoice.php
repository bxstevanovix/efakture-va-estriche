<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'customer_invoices';

    protected $fillable = [
        'id_invoice',
        'text',
        'company',
        'status',
        'price',
        'price_part',
        'debt',
        'currency',
        'pdf',
        'date_start',
        'date_end',
        'date_done',
        'square_meters',
        'address',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'company');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->debt = ($model->price ?: 0) - ($model->price_part ?: 0);
        });

        static::updating(function ($model) {
            $model->debt = ($model->price ?: 0) - ($model->price_part ?: 0);
        });
    }
}
