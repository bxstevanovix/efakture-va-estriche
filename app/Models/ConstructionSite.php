<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionSite extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'Aktivno',
        'planned' => 'Planirano',
        'paused' => 'Pauzirano',
        'finished' => 'Završeno',
    ];

    protected $fillable = [
        'name',
        'address',
        'customer_name',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function workLogs(): HasMany
    {
        return $this->hasMany(EmployeeWorkLog::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }
}
