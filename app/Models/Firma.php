<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Firma extends Model
{
    use SoftDeletes;

    protected $table = 'firme';

    protected $fillable = [
        'name',
        'address',
        'ort',
        'jib',
        'uid',
        'phone',
        'email',
        'currency',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function getFolderNameAttribute()
    {
        return $this->id . '-' . Str::slug($this->name);
    }
}
