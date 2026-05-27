<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeWorkLog extends Model
{
    public const STATUSES = [
        'active' => 'Aktivan',
        'vacation' => 'Odmor',
        'sick' => 'Bolovanje',
        'free' => 'Slobodan',
        'holiday' => 'Praznik',
    ];

    protected $fillable = [
        'employee_id',
        'construction_site_id',
        'work_date',
        'status',
        'start_time',
        'end_time',
        'break_minutes',
        'hours',
        'overtime_hours',
        'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
        'hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function constructionSite(): BelongsTo
    {
        return $this->belongsTo(ConstructionSite::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }
}
