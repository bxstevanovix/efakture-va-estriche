<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'Aktivan',
        'vacation' => 'Odmor',
        'sick' => 'Bolovanje',
        'free' => 'Slobodan',
        'inactive' => 'Neaktivan',
    ];

    public const CONTRACT_TYPES = [
        'full_time' => 'Puno radno vrijeme',
        'part_time' => 'Skraćeno radno vrijeme',
        'geringfuegig' => 'Minimalno zaposlenje',
        'temporary' => 'Ugovor na određeno',
        'freelancer' => 'Slobodni saradnik',
    ];

    public const DOCUMENT_TYPES = [
        'arbeitsvertrag' => 'Ugovor o radu',
        'krankenversicherung' => 'Zdravstveno osiguranje',
        'anmeldung' => 'Prijava radnika',
        'ausweis_reisepass' => 'Lična karta / pasoš',
        'arbeitserlaubnis' => 'Radna dozvola',
        'fuehrerschein' => 'Vozačka dozvola',
        'zertifikate' => 'Sertifikati',
        'bankdaten' => 'Bankovni podaci',
        'steuernummer' => 'Poreski broj',
        'sonstige' => 'Ostali dokumenti',
    ];

    public const REQUIRED_DOCUMENTS = [
        'arbeitsvertrag',
        'krankenversicherung',
        'anmeldung',
        'ausweis_reisepass',
    ];

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'birth_date',
        'nationality',
        'position',
        'entry_date',
        'contract_type',
        'hourly_wage',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
        'hourly_wage' => 'decimal:2',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(EmployeeWorkLog::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getStatusLabelAttribute(): string
    {
        return __(self::STATUSES[$this->status] ?? $this->status);
    }

    public function getContractTypeLabelAttribute(): string
    {
        return $this->contract_type
            ? __(self::CONTRACT_TYPES[$this->contract_type] ?? $this->contract_type)
            : '-';
    }

    public function missingRequiredDocumentTypes(): array
    {
        $existingTypes = $this->documents->pluck('document_type')->all();

        return array_values(array_diff(self::REQUIRED_DOCUMENTS, $existingTypes));
    }
}
