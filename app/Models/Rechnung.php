<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rechnung extends Model
{
    use SoftDeletes;

    protected $table = 'rechnungen';

    protected $fillable = [
        'id_invoice',
        'type',
        'firma',
        'price',
        'adress',
        'ort',
        'uid',
        'bvh',
        'auftragsnr',
        'ausfuhrungszeit',
        'invoice_url',
        'date_start',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_start' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', '.');
    }
}