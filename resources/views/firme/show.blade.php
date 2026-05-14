@extends('_layouts.layout')

@section('head_title', __('Profil firme'))

@section('content')

@php
    $formatCurrency = function ($value) {
        return number_format((float) $value, 2, ',', '.');
    };

    $totalInvoiceCount = $outgoingInvoiceCount + $incomingInvoiceCount;
    $hasOutgoingInvoices = $outgoingInvoiceCount > 0 || $outgoingOpenAmount > 0 || $outgoingPaidAmount > 0;
    $hasIncomingInvoices = $incomingInvoiceCount > 0 || $incomingOpenAmount > 0 || $incomingPaidAmount > 0;
    $outgoingDifference = $outgoingPaidAmount - $outgoingOpenAmount;
    $incomingDifference = $incomingPaidAmount - $incomingOpenAmount;
    $chartSegments = [];

    if ($hasOutgoingInvoices) {
        $chartSegments[] = ['label' => __('Izlazne plaćene'), 'value' => $outgoingPaidAmount, 'color' => '#2BC155'];
        $chartSegments[] = ['label' => __('Izlazne neplaćene'), 'value' => $outgoingOpenAmount, 'color' => '#FD5353'];
    }

    if ($hasIncomingInvoices) {
        $chartSegments[] = ['label' => __('Ulazne plaćene'), 'value' => $incomingPaidAmount, 'color' => '#40BAD5'];
        $chartSegments[] = ['label' => __('Ulazne neplaćene'), 'value' => $incomingOpenAmount, 'color' => '#FFA825'];
    }
@endphp

<style>
    .company-profile .profile-hero-card {
        position: relative;
        overflow: hidden;
        border: 0;
    }

    .company-profile .profile-hero-card:before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: var(--primary);
    }

    .company-profile .profile-hero {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
    }

    .company-profile .profile-left {
        flex: 1 1 auto;
        min-width: 0;
    }

    .company-profile .profile-main {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        min-width: 0;
    }

    .company-profile .profile-avatar {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--rgba-primary-1);
        color: var(--primary);
        font-size: 24px;
    }

    .company-profile .profile-title {
        font-size: 26px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
        overflow-wrap: anywhere;
    }

    .company-profile .profile-address {
        color: #6c757d;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .company-profile .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .company-profile .profile-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 34px;
        padding: 7px 10px;
        border-radius: 6px;
        background: #f6f7fb;
        color: #3f4b5b;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
        max-width: 100%;
    }

    .company-profile .profile-meta-item span {
        overflow-wrap: anywhere;
    }

    .company-profile .profile-actions {
        min-width: 190px;
        text-align: right;
    }

    .company-profile .metric-card {
        height: 100%;
    }

    .company-profile .finance-section .metric-card {
        margin-bottom: 18px;
    }

    .company-profile .finance-section {
        margin: 0 0 24px;
    }

    .company-profile .finance-row-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 3px 0 12px;
    }

    .company-profile .finance-row-label h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .company-profile .finance-row-label span {
        display: inline-flex;
        width: 10px;
        height: 34px;
        border-radius: 4px;
    }

    .company-profile .finance-row-label.outgoing span {
        background: #2BC155;
    }

    .company-profile .finance-row-label.incoming span {
        background: #40BAD5;
    }

    .company-profile .metric-label {
        color: #7e7e7e;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .company-profile .metric-value {
        font-size: 22px;
        font-weight: 700;
        margin: 8px 0 4px;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .company-profile .metric-subvalue {
        color: #7e7e7e;
        margin-bottom: 0;
        line-height: 1.45;
    }

    .company-profile .profile-grid {
        margin-bottom: 30px;
    }

    .company-profile .insight-card {
        height: 100%;
    }

    .company-profile .chart-box {
        position: relative;
        height: 270px;
    }

    .company-profile .chart-box canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .company-profile .summary-table td {
        vertical-align: middle;
        padding: 12px 0;
    }

    .company-profile .summary-table td:last-child {
        text-align: right;
        font-weight: 700;
    }

    .company-profile .file-table th,
    .company-profile .file-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .company-profile .documents-card {
        height: 100%;
    }

    .company-profile .documents-card .card-body {
        display: flex;
        flex-direction: column;
        min-height: 420px;
    }

    .company-profile .documents-card .table-responsive {
        flex: 1 1 auto;
        overflow-x: auto;
    }

    .company-profile .file-row {
        cursor: pointer;
    }

    .company-profile .file-row:hover {
        background-color: #f5f7fb;
    }

    .company-profile .file-toolbar {
        border-bottom: 1px solid #eef1f7;
        padding-bottom: 10px;
    }

    .company-profile .file-breadcrumb {
        font-size: 14px;
        line-height: 1.5;
    }

    .company-profile .file-breadcrumb span {
        color: #6c757d;
        cursor: pointer;
        transition: 0.2s;
    }

    .company-profile .file-breadcrumb span:hover {
        color: var(--primary);
    }

    .company-profile .file-breadcrumb .active {
        font-weight: 600;
        color: #212529;
        cursor: default;
    }

    .company-profile .file-breadcrumb .separator {
        margin: 0 6px;
        color: #adb5bd;
    }

    @media (max-width: 767px) {
        .company-profile .card-body {
            padding: 18px;
        }

        .company-profile .profile-hero {
            display: block;
        }

        .company-profile .profile-left {
            min-width: 0;
        }

        .company-profile .profile-main {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .company-profile .profile-avatar {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 7px;
            font-size: 20px;
        }

        .company-profile .profile-actions {
            margin-top: 14px;
            min-width: 0;
            text-align: left;
        }

        .company-profile .profile-actions .btn {
            width: calc(50% - 4px);
            margin-bottom: 0;
            margin-right: 0 !important;
        }

        .company-profile .profile-actions {
            display: flex;
            gap: 8px;
        }

        .company-profile .profile-title {
            font-size: 22px;
            line-height: 1.2;
        }

        .company-profile .profile-address {
            font-size: 13px;
        }

        .company-profile .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 12px;
        }

        .company-profile .profile-meta-item {
            width: auto;
            max-width: 100%;
            min-height: 30px;
            padding: 6px 8px;
            font-size: 12px;
            gap: 6px;
        }

        .company-profile .profile-meta-item i {
            font-size: 12px;
        }

        .company-profile .profile-meta-firmenbuch {
            display: none;
        }

        .company-profile .finance-section {
            margin-bottom: 26px;
        }

        .company-profile .finance-row-label {
            margin: 0 0 10px;
        }

        .company-profile .finance-row-label h4 {
            font-size: 16px;
        }

        .company-profile .finance-row-label span {
            width: 8px;
            height: 28px;
        }

        .company-profile .finance-section .row {
            margin-left: -6px;
            margin-right: -6px;
        }

        .company-profile .finance-section .row > [class*="col-"] {
            padding-left: 6px;
            padding-right: 6px;
            margin-bottom: 12px;
        }

        .company-profile .finance-section .metric-card {
            margin-bottom: 0;
        }

        .company-profile .metric-card .card-body {
            padding: 14px;
        }

        .company-profile .metric-label {
            font-size: 11px;
            line-height: 1.25;
        }

        .company-profile .metric-value {
            font-size: 18px;
            margin: 7px 0 3px;
        }

        .company-profile .metric-subvalue {
            font-size: 12px;
        }

        .company-profile .chart-box {
            height: 230px;
        }

        .company-profile .summary-table td {
            padding: 10px 0;
            font-size: 13px;
        }

        .company-profile .summary-table td:last-child {
            padding-left: 12px;
        }

        .company-profile .documents-card .card-body {
            min-height: 0;
        }

        .company-profile .documents-card {
            margin-top: 18px;
        }

        .company-profile .file-toolbar {
            display: block !important;
        }

        .company-profile .file-breadcrumb {
            margin-bottom: 10px;
        }

        .company-profile #company-btn-back {
            width: 100%;
        }

        .company-profile .documents-card .table-responsive {
            overflow: visible;
        }

        .company-profile .file-table {
            min-width: 0;
        }

        .company-profile .file-table,
        .company-profile .file-table tbody,
        .company-profile .file-table tr,
        .company-profile .file-table td {
            display: block;
            width: 100%;
        }

        .company-profile .file-table thead {
            display: none;
        }

        .company-profile .file-table tr {
            border: 1px solid #eef1f7;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background: #fff;
        }

        .company-profile .file-table td {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            border: 0;
            padding: 7px 0;
            white-space: normal;
            text-align: right !important;
        }

        .company-profile .file-table td:before {
            content: attr(data-label);
            color: #7e7e7e;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            min-width: 82px;
        }

        .company-profile .file-table td:first-child {
            display: block;
            text-align: left !important;
        }

        .company-profile .file-table td:first-child:before {
            display: block;
            margin-bottom: 6px;
        }

        .company-profile .file-table td .d-flex {
            min-width: 0;
        }

        .company-profile .file-table td .ms-2 {
            overflow-wrap: anywhere;
            white-space: normal;
        }

        .company-profile .file-table td .btn {
            min-width: 42px;
        }
    }

    @media (min-width: 430px) and (max-width: 767px) {
        .company-profile .finance-section .row > [class*="col-"] {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 429px) {
        .company-profile .finance-section .row > [class*="col-"] {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<div class="company-profile">
    <div class="card profile-hero-card">
        <div class="card-body">
            <div class="profile-hero">
                <div class="profile-left">
                    <div class="profile-main">
                        <div class="profile-avatar">
                            <i class="fa fa-building"></i>
                        </div>
                        <div>
                            <h2 class="profile-title">{{ $entity->name }}</h2>
                            <div class="profile-address">{{ $entity->address }}, {{ $entity->ort }}</div>
                        </div>
                    </div>

                    <div class="profile-meta">
                        <div class="profile-meta-item">
                            <i class="fa fa-id-card text-primary"></i>
                            <span>@lang('UID-Nummer'): {{ $entity->uid }}</span>
                        </div>
                        <div class="profile-meta-item profile-meta-firmenbuch">
                            <i class="fa fa-hashtag text-primary"></i>
                            <span>@lang('Firmenbuchnummer'): {{ $entity->jib }}</span>
                        </div>
                        <div class="profile-meta-item">
                            <i class="fa fa-phone text-primary"></i>
                            <span>{{ $entity->phone }}</span>
                        </div>
                        <div class="profile-meta-item">
                            <i class="fa fa-envelope text-primary"></i>
                            <span>{{ $entity->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="profile-actions text-end">
                    <a href="{{ route('firme.index') }}" class="btn btn-secondary btn-sm me-2">
                        <i class="fa fa-arrow-left me-1"></i>@lang('Nazad')
                    </a>
                    <a href="{{ route('firme.edit', ['entity' => $entity->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-pencil me-1"></i>@lang('Izmeni')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-grid">
        @if($hasOutgoingInvoices)
            <div class="finance-section">
                <div class="finance-row-label outgoing">
                    <span></span>
                    <h4>@lang('Izlazne fakture')</h4>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Za naplatu')</div>
                                <div class="metric-value text-danger">{{ $formatCurrency($outgoingOpenAmount) }} EUR</div>
                                <p class="metric-subvalue">@lang('Neplaćeno od kupaca')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Naplaćeno')</div>
                                <div class="metric-value text-success">{{ $formatCurrency($outgoingPaidAmount) }} EUR</div>
                                <p class="metric-subvalue">@lang('Uplaćene izlazne fakture')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Razlika')</div>
                                <div class="metric-value {{ $outgoingDifference >= 0 ? 'text-success' : 'text-danger' }}">{{ $formatCurrency($outgoingDifference) }} EUR</div>
                                <p class="metric-subvalue">@lang('Naplaćeno minus za naplatu')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Broj faktura')</div>
                                <div class="metric-value">{{ $outgoingInvoiceCount }}</div>
                                <p class="metric-subvalue">@lang('Ukupno izlaznih faktura')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($hasIncomingInvoices)
            <div class="finance-section">
                <div class="finance-row-label incoming">
                    <span></span>
                    <h4>@lang('Ulazne fakture')</h4>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Za plaćanje')</div>
                                <div class="metric-value text-danger">{{ $formatCurrency($incomingOpenAmount) }} EUR</div>
                                <p class="metric-subvalue">@lang('Neplaćeno dobavljačima')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Plaćeno')</div>
                                <div class="metric-value text-success">{{ $formatCurrency($incomingPaidAmount) }} EUR</div>
                                <p class="metric-subvalue">@lang('Plaćene ulazne fakture')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Razlika')</div>
                                <div class="metric-value {{ $incomingDifference >= 0 ? 'text-success' : 'text-danger' }}">{{ $formatCurrency($incomingDifference) }} EUR</div>
                                <p class="metric-subvalue">@lang('Plaćeno minus za plaćanje')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="metric-label">@lang('Broj faktura')</div>
                                <div class="metric-value">{{ $incomingInvoiceCount }}</div>
                                <p class="metric-subvalue">@lang('Ukupno ulaznih faktura')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card insight-card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('Plaćeno / neplaćeno')</h4>
                        <span>@lang('Pregled po istim stavkama kao u karticama')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        <canvas id="companyStatusChart"></canvas>
                    </div>
                    <table class="table summary-table mb-0">
                        <tbody>
                            @if($hasOutgoingInvoices)
                                <tr>
                                    <td>@lang('Izlazne plaćene')</td>
                                    <td class="text-success">{{ $formatCurrency($outgoingPaidAmount) }} EUR</td>
                                </tr>
                                <tr>
                                    <td>@lang('Izlazne neplaćene')</td>
                                    <td class="text-danger">{{ $formatCurrency($outgoingOpenAmount) }} EUR</td>
                                </tr>
                            @endif
                            @if($hasIncomingInvoices)
                                <tr>
                                    <td>@lang('Ulazne plaćene')</td>
                                    <td class="text-info">{{ $formatCurrency($incomingPaidAmount) }} EUR</td>
                                </tr>
                                <tr>
                                    <td>@lang('Ulazne neplaćene')</td>
                                    <td class="text-warning">{{ $formatCurrency($incomingOpenAmount) }} EUR</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card documents-card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('PDF dokumenti')</h4>
                        <span>@lang('Izlazne i ulazne fakture organizovane po folderima')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 file-toolbar">
                        <div id="company-breadcrumb" class="file-breadcrumb"></div>
                        <button id="company-btn-back" class="btn btn-sm btn-outline-secondary d-none" type="button">
                            <i class="fa fa-arrow-left"></i> @lang('Nazad')
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="company-file-table" class="table table-hover file-table mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('Naziv')</th>
                                    <th>@lang('Tip')</th>
                                    <th>@lang('Datum')</th>
                                    <th class="text-end">@lang('Akcija')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('footer_scripts')
<script src="{{ asset('files/vendor/chart-js/chart.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartSegments = @json($chartSegments);
    const chartValues = chartSegments.map(segment => Number(segment.value || 0));
    const chartTotal = chartValues.reduce((sum, value) => sum + value, 0);
    const chartElement = document.getElementById('companyStatusChart');
    const hasChartData = chartTotal > 0;

    function moneyLabel(value) {
        return new Intl.NumberFormat('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value) + ' EUR';
    }

    if (chartElement) {
        new Chart(chartElement, {
            type: 'doughnut',
            data: {
                labels: hasChartData ? chartSegments.map(segment => segment.label) : ['@lang('Nema podataka')'],
                datasets: [{
                    data: hasChartData ? chartValues : [1],
                    backgroundColor: hasChartData ? chartSegments.map(segment => segment.color) : ['#e9ecef'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        enabled: hasChartData,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + moneyLabel(context.parsed);
                            }
                        }
                    }
                }
            }
        });
    }
});

let companyId = {{ $entity->id }};
let currentCompanyPath = '';
const emptyFilesText = @json(__('Nema dokumenata za prikaz.'));
const fileTableLabels = {
    name: @json(__('Naziv')),
    type: @json(__('Tip')),
    date: @json(__('Datum')),
    action: @json(__('Akcija'))
};

function escapeHtml(value) {
    return $('<div>').text(value ?? '').html();
}

function loadCompanyFiles(path = '') {
    currentCompanyPath = path;

    $.get(`/file-list/${companyId}`, { path: path }, function(data) {
        let rows = '';

        if (path) {
            let parts = path.split('/');

            if (parts.length > 2) {
                $('#company-btn-back').removeClass('d-none');

                let parent = parts.slice(0, -1).join('/');
                $('#company-btn-back').off('click').on('click', function () {
                    loadCompanyFiles(parent);
                });
            } else {
                $('#company-btn-back').addClass('d-none');
            }
        } else {
            $('#company-btn-back').addClass('d-none');
        }

        data.forEach(item => {
            const itemPath = escapeHtml(item.path);
            const itemName = escapeHtml(item.name);
            const itemType = escapeHtml(item.type);
            const itemDate = escapeHtml(item.date);
            const icon = item.is_dir
                ? '<i class="fa fa-folder text-warning"></i>'
                : '<i class="fa fa-file-pdf text-primary"></i>';

            rows += `
                <tr class="file-row">
                    <td class="${item.is_dir ? 'js-open-folder' : ''}" data-label="${fileTableLabels.name}" data-path="${itemPath}">
                        <div class="d-flex align-items-center">
                            ${icon}
                            <span class="ms-2">${itemName}</span>
                        </div>
                    </td>
                    <td data-label="${fileTableLabels.type}">${itemType}</td>
                    <td data-label="${fileTableLabels.date}">${itemDate}</td>
                    <td class="text-end" data-label="${fileTableLabels.action}">
                        ${
                            item.is_dir
                            ? `<button class="btn btn-xs btn-info js-open-folder" type="button" data-path="${itemPath}">@lang('Otvori')</button>`
                            : `<a href="/file-manager/view?path=${encodeURIComponent(item.path)}" target="_blank" class="btn btn-sm btn-light" title="Preview">
                                    <i class="fa fa-eye"></i>
                               </a>`
                        }
                    </td>
                </tr>`;
        });

        if (!rows) {
            rows = `<tr><td colspan="4" class="text-center text-muted py-4" data-label="">${emptyFilesText}</td></tr>`;
        }

        $('#company-file-table tbody').html(rows);
        renderCompanyBreadcrumb(path);
    });
}

function renderCompanyBreadcrumb(path) {
    if (!path) {
        $('#company-breadcrumb').html(`
            <span class="active">
                <i class="fa fa-home me-1"></i> @lang('Fakture')
            </span>
        `);
        return;
    }

    let parts = path.split('/');
    let html = '';
    let current = '';

    parts.forEach((part, index) => {
        current += (index === 0 ? part : '/' + part);

        let isLast = index === parts.length - 1;
        let icon = !isLast
            ? '<i class="fa fa-folder text-warning me-1"></i>'
            : '<i class="fa fa-folder-open text-warning me-1"></i>';

        if (index === 0) {
            html += `
                <span class="active">
                    ${icon} ${formatCompanyBreadcrumbName(part)}
                </span>
            `;
        } else {
            html += `
                <span class="${isLast ? 'active' : 'js-breadcrumb-folder'}" data-path="${escapeHtml(current)}">
                    ${icon} ${formatCompanyBreadcrumbName(part)}
                </span>
            `;
        }

        if (!isLast) {
            html += `<span class="separator"> / </span>`;
        }
    });

    $('#company-breadcrumb').html(html);
}

function formatCompanyBreadcrumbName(name) {
    if (name === 'ausgangsrechnungen') return '@lang('Izlazne Fakture')';
    if (name === 'eingangsrechnungen') return '@lang('Ulazne Fakture')';

    return escapeHtml(name).replaceAll('-', ' ');
}

$(function(){
    loadCompanyFiles();

    $('#company-file-table').on('click', '.js-open-folder', function () {
        loadCompanyFiles($(this).data('path'));
    });

    $('#company-breadcrumb').on('click', '.js-breadcrumb-folder', function () {
        loadCompanyFiles($(this).data('path'));
    });
});
</script>
@endpush
