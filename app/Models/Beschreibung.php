<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beschreibung extends Model
{
    protected $table = 'beschreibung';

    protected $fillable = [
        'invoice_type',
        'invoice_id',
        'name',
        'qty',
        'price',
        'total',
    ];
}
