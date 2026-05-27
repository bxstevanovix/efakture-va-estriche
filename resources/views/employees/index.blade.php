@extends('_layouts.layout')

@section('head_title', __('Radnici'))

@section('content')
<style>
    .employees-page .metric-card {
        min-height: 112px;
    }

    .employees-page .metric-label {
        color: #7e7e7e;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .employees-page .metric-value {
        color: #2f3a4a;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .employees-page .employee-index-table {
        width: 100% !important;
    }

    .employees-page .employee-index-table td,
    .employees-page .employee-index-table th {
        vertical-align: middle;
    }

    .employees-page .employee-name {
        font-weight: 700;
        color: #2f3a4a;
    }

    .employees-page .employee-meta {
        color: #7e7e7e;
        font-size: 12px;
    }

    @media (max-width: 767px) {
        .employees-page .card-header {
            display: block;
        }

        .employees-page .card-header .btn {
            width: 100%;
            margin-top: 12px;
        }

        .employees-page .table-responsive {
            overflow: visible;
        }

        .employees-page .employee-index-table,
        .employees-page .employee-index-table tbody,
        .employees-page .employee-index-table tr,
        .employees-page .employee-index-table td {
            display: block;
            width: 100% !important;
        }

        .employees-page .employee-index-table thead {
            display: none;
        }

        .employees-page .employee-index-table tr {
            border: 1px solid #eef1f7;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
            background: #fff;
        }

        .employees-page .employee-index-table td {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            border: 0;
            padding: 7px 0;
            text-align: right !important;
            overflow-wrap: anywhere;
        }

        .employees-page .employee-index-table td:before {
            content: attr(data-label);
            color: #7e7e7e;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            min-width: 104px;
        }

        .employees-page .employee-index-table td:first-child {
            display: block;
            text-align: left !important;
        }

        .employees-page .employee-index-table td:first-child:before,
        .employees-page .employee-index-table td:last-child:before {
            display: none;
        }

        .employees-page .employee-index-table td:last-child {
            justify-content: flex-start;
            padding-top: 12px;
        }
    }
</style>

<div class="employees-page">
    <div class="row page-titles mx-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">@lang('Radnici')</a></li>
            <li class="breadcrumb-item active">@lang('Kontrolna tabla')</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Ukupno radnika')</div>
                    <div class="metric-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Aktivni')</div>
                    <div class="metric-value">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Sati') {{ $monthLabel }}</div>
                    <div class="metric-value">{{ number_format($stats['monthly_hours'], 1, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Prekovremeni sati')</div>
                    <div class="metric-value">{{ number_format($stats['monthly_overtime'], 1, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Dani odmora')</div>
                    <div class="metric-value">{{ $stats['vacation_days'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-label">@lang('Bolovanje')</div>
                    <div class="metric-value">{{ $stats['sick_days'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Spisak radnika')</h4>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        @lang('Kreiraj radnika')
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="employeesTable" class="display employee-index-table">
                            <thead>
                                <tr>
                                    <th>@lang('Radnik')</th>
                                    <th>@lang('Pozicija')</th>
                                    <th>@lang('Kontakt')</th>
                                    <th>@lang('Početak rada')</th>
                                    <th>@lang('Trenutno gradilište')</th>
                                    <th>@lang('Mjesec')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Dokumenti')</th>
                                    <th class="text-right">@lang('Opcije')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    @php
                                        $latestActiveLog = $employee->workLogs
                                            ->sortByDesc('work_date')
                                            ->first(fn ($log) => $log->status === 'active' && $log->constructionSite);
                                        $monthlyHours = $employee->workLogs->sum('hours');
                                        $monthlyOvertime = $employee->workLogs->sum('overtime_hours');
                                    @endphp
                                    <tr>
                                        <td data-label="{{ __('Radnik') }}">
                                            <div class="employee-name">{{ $employee->full_name }}</div>
                                            <div class="employee-meta">{{ $employee->employee_number ?: '-' }}</div>
                                        </td>
                                        <td data-label="{{ __('Pozicija') }}">{{ $employee->position ?: '-' }}</td>
                                        <td data-label="{{ __('Kontakt') }}">
                                            <div>{{ $employee->phone ?: '-' }}</div>
                                            <div class="employee-meta">{{ $employee->email ?: '' }}</div>
                                        </td>
                                        <td data-label="{{ __('Početak rada') }}">{{ $employee->entry_date?->format('d.m.Y') ?: '-' }}</td>
                                        <td data-label="{{ __('Trenutno gradilište') }}">
                                            {{ $latestActiveLog?->constructionSite?->name ?: '-' }}
                                        </td>
                                        <td data-label="{{ __('Mjesec') }}">
                                            <strong>{{ number_format($monthlyHours, 1, ',', '.') }}h</strong>
                                            <div class="employee-meta">@lang('Prekovremeno'): {{ number_format($monthlyOvertime, 1, ',', '.') }}h</div>
                                        </td>
                                        <td data-label="{{ __('Status') }}">
                                            <span class="badge light badge-primary">{{ $employee->status_label }}</span>
                                        </td>
                                        <td data-label="{{ __('Dokumenti') }}">{{ $employee->documents_count }}</td>
                                        <td data-label="{{ __('Opcije') }}" class="text-right">
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-info shadow btn-s sharp" title="@lang('Profil')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">@lang('Nema radnika')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    $(function() {
        @if($employees->isNotEmpty())
        if ($.fn.DataTable) {
            $('#employeesTable').DataTable({
                pageLength: 10,
                language: {
                    search: @json(__('Pretraga:')),
                    info: @json(__('Prikaz _START_ do _END_ od _TOTAL_ unosa')),
                    infoEmpty: @json(__('Nema dostupnih unosa')),
                    lengthMenu: @json(__('Prikaži _MENU_ unosa')),
                    zeroRecords: @json(__('Nema pronađenih unosa')),
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });
        }
        @endif
    });
</script>
@endpush
