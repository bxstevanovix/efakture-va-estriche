<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'document_type',
        'title',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return __(Employee::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type);
    }

    public function getFormattedSizeAttribute(): string
    {
        if (! $this->size) {
            return '-';
        }

        if ($this->size < 1024 * 1024) {
            return number_format($this->size / 1024, 1, ',', '.') . ' KB';
        }

        return number_format($this->size / (1024 * 1024), 1, ',', '.') . ' MB';
    }
}
