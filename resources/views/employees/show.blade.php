@extends('_layouts.layout')

@section('head_title', $entity->full_name)

@section('content')
<style>
    .employee-profile-page .profile-value {
        color: #2f3a4a;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .employee-profile-page .profile-label {
        color: #7e7e7e;
        font-size: 12px;
    }

    .employee-profile-page .document-badge {
        display: inline-block;
        margin: 0 6px 6px 0;
    }

    .employee-profile-page .metric-card {
        min-height: 104px;
    }

    .employee-profile-page .metric-label {
        color: #7e7e7e;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .employee-profile-page .metric-value {
        color: #2f3a4a;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
    }

    .employee-profile-page .work-log-table td,
    .employee-profile-page .work-log-table th {
        vertical-align: middle;
    }

    .employee-profile-page .work-log-form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 8px;
        border-top: 1px solid #eef1f7;
    }

    @media (max-width: 767px) {
        .employee-profile-page .card-header {
            display: block;
        }

        .employee-profile-page .card-header .btn {
            width: 100%;
            margin-top: 12px;
        }

        .employee-profile-page .work-log-form-actions {
            display: block;
        }

        .employee-profile-page .work-log-form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="employee-profile-page">
    <div class="row page-titles mx-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">@lang('Radnici')</a></li>
            <li class="breadcrumb-item active">{{ $entity->full_name }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">{{ $entity->full_name }}</h4>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        @lang('Nazad')
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <div class="profile-value">{{ $entity->status_label }}</div>
                            <div class="profile-label">@lang('Status')</div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <div class="profile-value">{{ $entity->position ?: '-' }}</div>
                            <div class="profile-label">@lang('Pozicija')</div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <div class="profile-value">{{ $entity->hourly_wage !== null ? number_format((float) $entity->hourly_wage, 2, ',', '.') . ' EUR' : '-' }}</div>
                            <div class="profile-label">@lang('Satnica')</div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <div class="profile-value">{{ $entity->entry_date?->format('d.m.Y') ?: '-' }}</div>
                            <div class="profile-label">@lang('Datum početka rada')</div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->employee_number ?: '-' }}</div>
                            <div class="profile-label">@lang('Broj radnika')</div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->phone ?: '-' }}</div>
                            <div class="profile-label">@lang('Broj telefona')</div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->email ?: '-' }}</div>
                            <div class="profile-label">@lang('E-mail')</div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->birth_date?->format('d.m.Y') ?: '-' }}</div>
                            <div class="profile-label">@lang('Datum rođenja')</div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->nationality ?: '-' }}</div>
                            <div class="profile-label">@lang('Državljanstvo')</div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="profile-value">{{ $entity->contract_type_label }}</div>
                            <div class="profile-label">@lang('Tip ugovora')</div>
                        </div>
                        <div class="mb-3 col-12">
                            <div class="profile-value">{{ $entity->address ?: '-' }}</div>
                            <div class="profile-label">@lang('Adresa')</div>
                        </div>
                    </div>

                    @if($entity->notes)
                        <hr>
                        <div>{{ $entity->notes }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Obavezni dokumenti')</h4>
                </div>
                <div class="card-body">
                    @forelse($missingDocuments as $type)
                        <span class="badge light badge-warning document-badge">{{ __($documentTypes[$type] ?? $type) }}</span>
                    @empty
                        <span class="badge light badge-success">@lang('Kompletno')</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Sati ovaj mjesec')</div>
                    <div class="metric-value">{{ number_format($monthlyStats['hours'], 1, ',', '.') }}h</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Prekovremeni sati')</div>
                    <div class="metric-value">{{ number_format($monthlyStats['overtime'], 1, ',', '.') }}h</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Radni dani')</div>
                    <div class="metric-value">{{ $monthlyStats['active_days'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Odmor')</div>
                    <div class="metric-value">{{ $monthlyStats['vacation_days'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Bolovanje')</div>
                    <div class="metric-value">{{ $monthlyStats['sick_days'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Trenutno gradilište')</div>
                    <div class="metric-value" style="font-size: 16px;">{{ $monthlyStats['current_site'] ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Dnevnik rada')</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('employees.work_logs.store', $entity) }}" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">@lang('Datum')</label>
                                <input type="date" name="work_date" class="form-control @errorClass('work_date', 'is-invalid')" value="{{ old('work_date', now()->format('Y-m-d')) }}" required>
                                @error('work_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">@lang('Status')</label>
                                <select id="workLogStatus" name="status" class="form-control @errorClass('status', 'is-invalid')" required>
                                    @foreach($workStatuses as $status => $label)
                                        <option value="{{ $status }}" @selected(old('status', 'active') === $status)>{{ __($label) }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="activeWorkFields">
                            <div class="mb-3">
                                <label class="form-label">@lang('Gradilište')</label>
                                <select name="construction_site_id" class="form-control @errorClass('construction_site_id', 'is-invalid')">
                                    <option value="">-</option>
                                    @foreach($constructionSites as $site)
                                        <option value="{{ $site->id }}" @selected(old('construction_site_id') == $site->id)>{{ $site->name }}</option>
                                    @endforeach
                                </select>
                                @error('construction_site_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">@lang('Novo gradilište')</label>
                                    <input type="text" name="new_site_name" class="form-control @errorClass('new_site_name', 'is-invalid')" value="{{ old('new_site_name') }}" maxlength="190">
                                    @error('new_site_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">@lang('Adresa')</label>
                                    <input type="text" name="new_site_address" class="form-control @errorClass('new_site_address', 'is-invalid')" value="{{ old('new_site_address') }}" maxlength="255">
                                    @error('new_site_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">@lang('Od')</label>
                                    <input type="time" name="start_time" class="form-control @errorClass('start_time', 'is-invalid')" value="{{ old('start_time', '07:00') }}">
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">@lang('Do')</label>
                                    <input type="time" name="end_time" class="form-control @errorClass('end_time', 'is-invalid')" value="{{ old('end_time', '16:00') }}">
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">@lang('Pauza min')</label>
                                    <input type="number" name="break_minutes" class="form-control @errorClass('break_minutes', 'is-invalid')" value="{{ old('break_minutes', 60) }}" min="0" max="600">
                                    @error('break_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">@lang('Sati')</label>
                                    <input type="text" name="hours" inputmode="decimal" class="form-control @errorClass('hours', 'is-invalid')" value="{{ old('hours') }}" placeholder="@lang('Automatski')">
                                    @error('hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">@lang('Prekovremeni sati')</label>
                                    <input type="text" name="overtime_hours" inputmode="decimal" class="form-control @errorClass('overtime_hours', 'is-invalid')" value="{{ old('overtime_hours') }}" placeholder="@lang('Automatski')">
                                    @error('overtime_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">@lang('Napomena')</label>
                            <textarea name="notes" class="form-control @errorClass('notes', 'is-invalid')" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="work-log-form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i>
                                @lang('Sačuvaj')
                            </button>
                        </div>
                    </form>
                </div>
	            </div>

	            <div class="card h-auto">
	                <div class="card-header">
	                    <h4 class="card-title">@lang('Automatsko popunjavanje')</h4>
	                </div>
	                <div class="card-body">
	                    <form method="post" action="{{ route('employees.work_logs.generate', $entity) }}" autocomplete="off">
	                        @csrf
	                        <div class="row">
	                            <div class="mb-3 col-md-6">
	                                <label class="form-label">@lang('Od datuma')</label>
	                                <input type="date" name="from_date" class="form-control @errorClass('from_date', 'is-invalid')" value="{{ old('from_date', now()->startOfMonth()->format('Y-m-d')) }}" required>
	                                @error('from_date')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                            <div class="mb-3 col-md-6">
	                                <label class="form-label">@lang('Do datuma')</label>
	                                <input type="date" name="to_date" class="form-control @errorClass('to_date', 'is-invalid')" value="{{ old('to_date', now()->endOfMonth()->format('Y-m-d')) }}" required>
	                                @error('to_date')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="mb-3">
	                            <label class="form-label">@lang('Početno gradilište')</label>
	                            <select name="construction_site_id" class="form-control @errorClass('construction_site_id', 'is-invalid')">
	                                <option value="">@lang('Uzmi prethodno gradilište')</option>
	                                @foreach($constructionSites as $site)
	                                    <option value="{{ $site->id }}" @selected(old('construction_site_id') == $site->id)>{{ $site->name }}</option>
	                                @endforeach
	                            </select>
	                            @error('construction_site_id')
	                                <div class="invalid-feedback">{{ $message }}</div>
	                            @enderror
	                        </div>

	                        <div class="row">
	                            <div class="mb-3 col-md-4">
	                                <label class="form-label">@lang('Od')</label>
	                                <input type="time" name="start_time" class="form-control @errorClass('start_time', 'is-invalid')" value="{{ old('start_time', '07:00') }}" required>
	                                @error('start_time')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                            <div class="mb-3 col-md-4">
	                                <label class="form-label">@lang('Do')</label>
	                                <input type="time" name="end_time" class="form-control @errorClass('end_time', 'is-invalid')" value="{{ old('end_time', '16:00') }}" required>
	                                @error('end_time')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                            <div class="mb-3 col-md-4">
	                                <label class="form-label">@lang('Pauza min')</label>
	                                <input type="number" name="break_minutes" class="form-control @errorClass('break_minutes', 'is-invalid')" value="{{ old('break_minutes', 60) }}" min="0" max="600">
	                                @error('break_minutes')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-check mb-3">
	                            <input type="hidden" name="include_weekends" value="0">
	                            <input type="checkbox" name="include_weekends" value="1" class="form-check-input" id="includeWeekends" @checked(old('include_weekends'))>
	                            <label class="form-check-label" for="includeWeekends">@lang('Uključi vikende')</label>
	                        </div>

	                        <div class="work-log-form-actions">
	                            <button type="submit" class="btn btn-primary">
	                                <i class="fa fa-calendar-plus"></i>
	                                @lang('Popuni dane')
	                            </button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>

	        <div class="col-xl-8">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Mjesečni pregled')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md work-log-table">
                            <thead>
                                <tr>
                                    <th>@lang('Datum')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Gradilište')</th>
                                    <th>@lang('Od/Do')</th>
                                    <th>@lang('Sati')</th>
                                    <th>@lang('Prekovremeno')</th>
                                    <th class="text-right">@lang('Opcije')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entity->workLogs as $log)
                                    <tr>
                                        <td>{{ $log->work_date?->format('d.m.Y') }}</td>
                                        <td><span class="badge light badge-primary">{{ $log->status_label }}</span></td>
                                        <td>{{ $log->constructionSite?->name ?: '-' }}</td>
                                        <td>
                                            @if($log->status === 'active')
                                                {{ $log->start_time ? substr($log->start_time, 0, 5) : '-' }} - {{ $log->end_time ? substr($log->end_time, 0, 5) : '-' }}
                                                <div class="text-muted small">@lang('Pauza'): {{ $log->break_minutes }} min</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ number_format((float) $log->hours, 2, ',', '.') }}</td>
                                        <td>{{ number_format((float) $log->overtime_hours, 2, ',', '.') }}</td>
                                        <td class="text-right">
                                            <form method="post" action="{{ route('employees.work_logs.delete', [$entity, $log]) }}" onsubmit="return confirm('{{ __('Obrisati unos?') }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger shadow btn-s sharp">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @if($log->notes)
                                        <tr>
                                            <td></td>
                                            <td colspan="6" class="text-muted small">{{ $log->notes }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">@lang('Nema unosa za ovaj mjesec')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Dokumentacija radnika')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th>@lang('Dokument')</th>
                                    <th>@lang('Fajl')</th>
                                    <th>@lang('Važi do')</th>
                                    <th>@lang('Veličina')</th>
                                    <th class="text-right">@lang('Opcije')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entity->documents as $document)
                                    <tr>
                                        <td>
                                            <strong>{{ $document->document_type_label }}</strong>
                                            @if($document->notes)
                                                <div class="text-muted small">{{ $document->notes }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">
                                                {{ $document->original_name }}
                                            </a>
                                        </td>
                                        <td>{{ $document->expires_at?->format('d.m.Y') ?: '-' }}</td>
                                        <td>{{ $document->formatted_size }}</td>
                                        <td class="text-right">
                                            <form method="post" action="{{ route('employees.documents.delete', [$entity, $document]) }}" onsubmit="return confirm('{{ __('Obrisati dokument?') }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger shadow btn-s sharp">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">@lang('Nema dokumenata')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Dodaj dokument')</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('employees.documents.upload', $entity) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">@lang('Tip dokumenta')</label>
                            <select name="document_type" class="form-control @errorClass('document_type', 'is-invalid')" required>
                                @foreach($documentTypes as $type => $label)
                                    <option value="{{ $type }}">{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('Fajl')</label>
                            <input type="file" name="file" class="form-control @errorClass('file', 'is-invalid')" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('Važi do')</label>
                            <input type="date" name="expires_at" class="form-control @errorClass('expires_at', 'is-invalid')">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('Napomena')</label>
                            <textarea name="notes" class="form-control @errorClass('notes', 'is-invalid')" rows="3"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa fa-upload"></i>
                            @lang('Dodaj')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    $(function() {
        const status = $('#workLogStatus');
        const activeFields = $('#activeWorkFields');

        function syncWorkLogFields() {
            activeFields.toggle(status.val() === 'active');
        }

        status.on('change', syncWorkLogFields);
        syncWorkLogFields();
    });
</script>
@endpush
