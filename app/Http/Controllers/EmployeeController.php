<?php

namespace App\Http\Controllers;

use App\Models\ConstructionSite;
use App\Models\Employee as Entity;
use App\Models\EmployeeDocument;
use App\Models\EmployeeWorkLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $employees = Entity::with('documents')
            ->with(['workLogs' => function ($query) use ($monthStart, $monthEnd) {
                $query->with('constructionSite')
                    ->whereBetween('work_date', [$monthStart, $monthEnd]);
            }])
            ->withCount('documents')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $stats = [
            'total' => $employees->count(),
            'active' => $employees->where('status', 'active')->count(),
            'missing_documents' => $employees
                ->filter(fn (Entity $employee) => count($employee->missingRequiredDocumentTypes()) > 0)
                ->count(),
            'monthly_hours' => $employees->sum(fn (Entity $employee) => $employee->workLogs->sum('hours')),
            'monthly_overtime' => $employees->sum(fn (Entity $employee) => $employee->workLogs->sum('overtime_hours')),
            'vacation_days' => $employees->sum(fn (Entity $employee) => $employee->workLogs->where('status', 'vacation')->count()),
            'sick_days' => $employees->sum(fn (Entity $employee) => $employee->workLogs->where('status', 'sick')->count()),
        ];

        return view('employees.index', [
            'employees' => $employees,
            'stats' => $stats,
            'monthLabel' => now()->format('m.Y'),
        ]);
    }

    public function create()
    {
        return view('employees.create', [
            'entity' => new Entity(),
            'statuses' => Entity::STATUSES,
            'contractTypes' => Entity::CONTRACT_TYPES,
            'documentTypes' => Entity::DOCUMENT_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->employeeRules());
        unset($data['documents']);

        $entity = Entity::create($data);
        $this->storeDocumentsFromCreateForm($request, $entity);

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Radnik je uspešno sačuvan!'));
    }

    public function show(Entity $entity)
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $entity->load([
            'documents',
            'workLogs' => function ($query) use ($monthStart, $monthEnd) {
                $query->with('constructionSite')
                    ->whereBetween('work_date', [$monthStart, $monthEnd])
                    ->orderByDesc('work_date');
            },
        ]);

        $monthlyLogs = $entity->workLogs;
        $latestActiveLog = $monthlyLogs->first(fn (EmployeeWorkLog $log) => $log->status === 'active' && $log->constructionSite);

        return view('employees.show', [
            'entity' => $entity,
            'documentTypes' => Entity::DOCUMENT_TYPES,
            'missingDocuments' => $entity->missingRequiredDocumentTypes(),
            'constructionSites' => ConstructionSite::where('status', 'active')->orderBy('name')->get(),
            'workStatuses' => EmployeeWorkLog::STATUSES,
            'monthlyStats' => [
                'hours' => $monthlyLogs->sum('hours'),
                'overtime' => $monthlyLogs->sum('overtime_hours'),
                'vacation_days' => $monthlyLogs->where('status', 'vacation')->count(),
                'sick_days' => $monthlyLogs->where('status', 'sick')->count(),
                'active_days' => $monthlyLogs->where('status', 'active')->count(),
                'current_site' => $latestActiveLog?->constructionSite?->name,
            ],
        ]);
    }

    public function storeWorkLog(Request $request, Entity $entity)
    {
        $data = $request->validate([
            'work_date' => ['required', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(EmployeeWorkLog::STATUSES))],
            'construction_site_id' => ['nullable', 'integer', 'exists:construction_sites,id'],
            'new_site_name' => ['nullable', 'string', 'max:190'],
            'new_site_address' => ['nullable', 'string', 'max:255'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_if:status,active'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_if:status,active'],
            'break_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'hours' => ['nullable', 'string', 'max:20', 'regex:/^\d{1,3}([,.]\d{1,2})?$/'],
            'overtime_hours' => ['nullable', 'string', 'max:20', 'regex:/^\d{1,3}([,.]\d{1,2})?$/'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (
            $data['status'] === 'active'
            && empty($data['construction_site_id'])
            && trim((string) ($data['new_site_name'] ?? '')) === ''
        ) {
            return back()
                ->withErrors(['construction_site_id' => __('Izaberite gradilište ili unesite novo gradilište.')])
                ->withInput();
        }

        $siteId = $this->workLogConstructionSiteId($data);
        $hours = $this->decimalInput($data['hours'] ?? null);
        $overtime = $this->decimalInput($data['overtime_hours'] ?? null);

        if ($data['status'] !== 'active') {
            $siteId = null;
            $data['start_time'] = null;
            $data['end_time'] = null;
            $data['break_minutes'] = 0;
            $hours = 0;
            $overtime = 0;
        } else {
            $data['break_minutes'] = (int) ($data['break_minutes'] ?? 0);
            $hours ??= $this->calculateHours($data['work_date'], $data['start_time'], $data['end_time'], $data['break_minutes']);
            $overtime ??= max(0, $hours - 8);
        }

        $entity->workLogs()->updateOrCreate(
            ['work_date' => $data['work_date']],
            [
                'construction_site_id' => $siteId,
                'status' => $data['status'],
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'break_minutes' => $data['break_minutes'] ?? 0,
                'hours' => $hours ?? 0,
                'overtime_hours' => $overtime ?? 0,
                'notes' => $data['notes'] ?? null,
            ]
        );

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Dnevnik rada je uspešno sačuvan!'));
    }

    public function generateDefaultWorkLogs(Request $request, Entity $entity)
    {
        $data = $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'construction_site_id' => ['nullable', 'integer', 'exists:construction_sites,id'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'break_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'include_weekends' => ['nullable', 'boolean'],
        ]);

        $from = Carbon::parse($data['from_date'])->startOfDay();
        $to = Carbon::parse($data['to_date'])->startOfDay();

        if ($from->diffInDays($to) > 92) {
            return back()
                ->withErrors(['to_date' => __('Period ne može biti duži od 3 mjeseca.')])
                ->withInput();
        }

        $currentSiteId = $data['construction_site_id'] ?? $this->latestConstructionSiteIdBefore($entity, $from);

        if (! $currentSiteId) {
            return back()
                ->withErrors(['construction_site_id' => __('Nema prethodnog gradilišta. Izaberite početno gradilište.')])
                ->withInput();
        }

        $created = 0;
        $skipped = 0;
        $includeWeekends = (bool) ($data['include_weekends'] ?? false);
        $breakMinutes = (int) ($data['break_minutes'] ?? 60);
        $hours = $this->calculateHours($from->toDateString(), $data['start_time'], $data['end_time'], $breakMinutes);

        for ($date = $from->copy(); $date->lessThanOrEqualTo($to); $date->addDay()) {
            if (! $includeWeekends && $date->isWeekend()) {
                $skipped++;
                continue;
            }

            $existingLog = $entity->workLogs()
                ->whereDate('work_date', $date->toDateString())
                ->first();

            if ($existingLog) {
                if ($existingLog->status === 'active' && $existingLog->construction_site_id) {
                    $currentSiteId = $existingLog->construction_site_id;
                }

                $skipped++;
                continue;
            }

            $entity->workLogs()->create([
                'construction_site_id' => $currentSiteId,
                'work_date' => $date->toDateString(),
                'status' => 'active',
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'break_minutes' => $breakMinutes,
                'hours' => $hours,
                'overtime_hours' => max(0, $hours - 8),
            ]);

            $created++;
        }

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Automatski dnevnik je uspešno popunjen. Dodano: :created, preskočeno: :skipped.', [
                'created' => $created,
                'skipped' => $skipped,
            ]));
    }

    public function deleteWorkLog(Entity $entity, EmployeeWorkLog $workLog)
    {
        if ($workLog->employee_id !== $entity->id) {
            abort(404);
        }

        $workLog->delete();

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Dnevnik rada je uspešno obrisan!'));
    }

    public function uploadDocument(Request $request, Entity $entity)
    {
        $data = $request->validate([
            'document_type' => ['required', 'string', Rule::in(array_keys(Entity::DOCUMENT_TYPES))],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'expires_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->storeDocument(
            $entity,
            $data['document_type'],
            $request->file('file'),
            $data['expires_at'] ?? null,
            $data['notes'] ?? null
        );

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Dokument je uspešno sačuvan!'));
    }

    public function deleteDocument(Entity $entity, EmployeeDocument $document)
    {
        if ($document->employee_id !== $entity->id) {
            abort(404);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()
            ->route('employees.show', $entity)
            ->with('success', __('Dokument je uspešno obrisan!'));
    }

    private function employeeRules(): array
    {
        return [
            'employee_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_number')->whereNull('deleted_at'),
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:120'],
            'entry_date' => ['nullable', 'date'],
            'contract_type' => ['nullable', 'string', Rule::in(array_keys(Entity::CONTRACT_TYPES))],
            'hourly_wage' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'status' => ['required', 'string', Rule::in(array_keys(Entity::STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'documents' => ['nullable', 'array'],
            'documents.*.type' => ['nullable', 'string', Rule::in(array_keys(Entity::DOCUMENT_TYPES))],
            'documents.*.file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'documents.*.expires_at' => ['nullable', 'date'],
            'documents.*.notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function workLogConstructionSiteId(array $data): ?int
    {
        $newSiteName = trim((string) ($data['new_site_name'] ?? ''));

        if ($newSiteName !== '') {
            $site = ConstructionSite::firstOrCreate(
                ['name' => $newSiteName],
                [
                    'address' => $data['new_site_address'] ?? null,
                    'status' => 'active',
                ]
            );

            return $site->id;
        }

        return $data['construction_site_id'] ?? null;
    }

    private function decimalInput(?string $value): ?float
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return (float) str_replace(',', '.', $value);
    }

    private function calculateHours(string $date, string $startTime, string $endTime, int $breakMinutes): float
    {
        $start = Carbon::parse($date . ' ' . $startTime);
        $end = Carbon::parse($date . ' ' . $endTime);

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        $minutes = max(0, $start->diffInMinutes($end) - $breakMinutes);

        return round($minutes / 60, 2);
    }

    private function latestConstructionSiteIdBefore(Entity $entity, Carbon $date): ?int
    {
        return $entity->workLogs()
            ->where('status', 'active')
            ->whereNotNull('construction_site_id')
            ->whereDate('work_date', '<', $date->toDateString())
            ->orderByDesc('work_date')
            ->value('construction_site_id');
    }

    private function storeDocumentsFromCreateForm(Request $request, Entity $entity): void
    {
        $documents = $request->input('documents', []);
        $files = $request->file('documents', []);

        foreach ($documents as $index => $documentData) {
            $file = $files[$index]['file'] ?? null;

            if (! $file instanceof UploadedFile) {
                continue;
            }

            $this->storeDocument(
                $entity,
                $documentData['type'] ?? 'sonstige',
                $file,
                $documentData['expires_at'] ?? null,
                $documentData['notes'] ?? null
            );
        }
    }

    private function storeDocument(
        Entity $entity,
        string $documentType,
        UploadedFile $file,
        ?string $expiresAt = null,
        ?string $notes = null
    ): EmployeeDocument {
        $folder = 'mitarbeiter/' . $entity->id . '-' . (Str::slug($entity->full_name) ?: 'employee');
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: $documentType;
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'file';
        $fileName = $this->uniquePublicFileName($folder, $baseName . '.' . $extension);
        $path = $file->storeAs($folder, $fileName, 'public');

        return $entity->documents()->create([
            'document_type' => $documentType,
            'title' => Entity::DOCUMENT_TYPES[$documentType] ?? $documentType,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'expires_at' => $expiresAt,
            'notes' => $notes,
        ]);
    }

    private function uniquePublicFileName(string $folder, string $fileName): string
    {
        $original = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $candidate = $fileName;
        $counter = 1;

        while (Storage::disk('public')->exists($folder . '/' . $candidate)) {
            $candidate = $original . '-' . $counter . ($extension ? '.' . $extension : '');
            $counter++;
        }

        return $candidate;
    }
}
