@extends('_layouts.layout')

@section('head_title', __('Dashboard'))

@push('head_links')

@endpush

@section('content')

@php
    $outgoingOpenAmount = (float) $invoicesPrice0;
    $outgoingPaidAmount = (float) $invoicesPrice1;
    $incomingOpenAmount = (float) $procurementPrice0;
    $incomingPaidAmount = (float) $procurementPrice1;

    $totalReceivables = $outgoingOpenAmount + $outgoingPaidAmount;
    $totalPayables = $incomingOpenAmount + $incomingPaidAmount;
    $expectedBalance = $outgoingOpenAmount - $incomingOpenAmount;
    $netPaidCashflow = $outgoingPaidAmount - $incomingPaidAmount;

    $outgoingCollectionRate = $totalReceivables > 0 ? round(($outgoingPaidAmount / $totalReceivables) * 100) : 0;
    $incomingPaidRate = $totalPayables > 0 ? round(($incomingPaidAmount / $totalPayables) * 100) : 0;
    $outgoingInvoicesCount = $invoicesStatus0 + $invoicesStatus1;
    $incomingInvoicesCount = $procurementStatus0 + $procurementStatus1;
    $outgoingPaidInvoiceRate = $outgoingInvoicesCount > 0 ? round(($invoicesStatus1 / $outgoingInvoicesCount) * 100) : 0;
    $outgoingUnpaidInvoiceRate = $outgoingInvoicesCount > 0 ? round(($invoicesStatus0 / $outgoingInvoicesCount) * 100) : 0;
    $incomingPaidInvoiceRate = $incomingInvoicesCount > 0 ? round(($procurementStatus1 / $incomingInvoicesCount) * 100) : 0;
    $incomingUnpaidInvoiceRate = $incomingInvoicesCount > 0 ? round(($procurementStatus0 / $incomingInvoicesCount) * 100) : 0;

    $formatCurrency = function ($value) {
        return number_format((float) $value, 2, ',', '.');
    };
@endphp

<style>
    .dashboard-page .dashboard-hero {
        border-radius: 8px;
    }

    .dashboard-page .metric-card {
        height: calc(100% - 1.875rem);
    }

    .dashboard-page .metric-card .card-body {
        padding: 20px;
    }

    .dashboard-page .metric-topline {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 16px;
    }

    .dashboard-page .metric-label {
        color: #7e7e7e;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .dashboard-page .metric-value {
        font-size: 25px;
        line-height: 1.15;
        font-weight: 700;
        margin-bottom: 0;
        word-break: break-word;
    }

    .dashboard-page .metric-subvalue {
        color: #7e7e7e;
        font-size: 13px;
        margin-top: 8px;
        margin-bottom: 0;
    }

    .dashboard-page .metric-progress {
        height: 7px;
        border-radius: 99px;
        background: #f1f1f1;
        overflow: hidden;
    }

    .dashboard-page .metric-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
    }

    .dashboard-chart {
        position: relative;
        width: 100%;
        height: 320px;
    }

    .dashboard-chart-sm {
        height: 260px;
    }

    .dashboard-chart canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .dashboard-date {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .dashboard-date .btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-date input {
        width: 136px;
        min-width: 136px;
    }

    .dashboard-page .table {
        width: 100% !important;
        margin-bottom: 0;
    }

    .dashboard-page .card-table td,
    .dashboard-page .card-table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .dashboard-page .summary-table td {
        padding: 12px 0;
        border-color: #f1f1f1;
    }

    .dashboard-page .summary-table td:last-child {
        text-align: right;
        font-weight: 700;
    }

    .dashboard-page .cashflow-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .dashboard-page .cashflow-summary-item {
        background: #fbfbfb;
        border: 1px solid #f1f1f1;
        border-radius: 8px;
        padding: 14px 16px;
    }

    .dashboard-page .cashflow-summary-item span {
        color: #7e7e7e;
        display: block;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .dashboard-page .cashflow-summary-item strong {
        color: #231C3A;
        display: block;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.2;
        word-break: break-word;
    }

    .dashboard-page .cashflow-summary-item i {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 7px;
    }

    .dashboard-page .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .dashboard-page .quick-actions .btn {
        justify-content: flex-start;
        text-align: left;
        padding: 12px 14px;
        border-radius: 8px;
        min-height: 42px;
    }

    .dashboard-page .quick-actions i {
        width: 18px;
        margin-right: 8px;
    }

    @media (max-width: 991px) {
        .dashboard-date {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .dashboard-page .metric-value {
            font-size: 22px;
        }

        .dashboard-chart {
            height: 250px;
        }

        .dashboard-chart-sm {
            height: 230px;
        }

        .dashboard-page .cashflow-summary {
            grid-template-columns: 1fr;
        }

        .dashboard-page .quick-actions {
            grid-template-columns: 1fr;
        }

        .prvi-grafikon {
            display: none;
        }
    }
</style>

<div class="dashboard-page">
    <div class="card dashboard-hero">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h2 class="mb-1">@lang('Finansijski pregled faktura')</h2>
                    <span>@lang('Kratak pregled naplate, obaveza, otvorenih rokova i mesečnog toka novca.')</span>
                </div>
                <div class="col-lg-5 mt-3 mt-lg-0 text-lg-end">
                    <a href="{{ route('customer-invoices.create') }}" class="btn btn-primary btn-sm me-2 mb-2">
                        <i class="fa fa-plus me-1"></i>@lang('Nova izlazna faktura')
                    </a>
                    <a href="{{ route('customer-invoices.reports') }}" class="btn btn-outline-primary btn-sm mb-2">
                        <i class="fa fa-chart-line me-1"></i>@lang('Izveštaji')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-topline">
                        <div>
                            <div class="metric-label">@lang('Izlazne neplaćene')</div>
                            <h3 class="metric-value">{{ $formatCurrency($outgoingOpenAmount) }} EUR</h3>
                            <p class="metric-subvalue">{{ $invoicesStatus0 }} @lang('neplaćenih izlaznih faktura')</p>
                        </div>
                        <div class="invoice-icon bgl-danger">
                            <span><i class="flaticon-project fs-22 text-danger"></i></span>
                        </div>
                    </div>
                    <div class="metric-progress"><span style="width: {{ min($outgoingUnpaidInvoiceRate, 100) }}%; background: #FD5353;"></span></div>
                    <p class="metric-subvalue">{{ $outgoingUnpaidInvoiceRate }}% @lang('izlaznih faktura nije plaćeno')</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-topline">
                        <div>
                            <div class="metric-label">@lang('Izlazne plaćene')</div>
                            <h3 class="metric-value">{{ $formatCurrency($outgoingPaidAmount) }} EUR</h3>
                            <p class="metric-subvalue">{{ $invoicesStatus1 }} @lang('plaćenih izlaznih faktura')</p>
                        </div>
                        <div class="invoice-icon bgl-success">
                            <span><i class="flaticon-project fs-22 text-success"></i></span>
                        </div>
                    </div>
                    <div class="metric-progress"><span style="width: {{ min($outgoingPaidInvoiceRate, 100) }}%; background: #2BC155;"></span></div>
                    <p class="metric-subvalue">{{ $outgoingPaidInvoiceRate }}% @lang('izlaznih faktura je plaćeno')</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-topline">
                        <div>
                            <div class="metric-label">@lang('Ulazne neplaćene')</div>
                            <h3 class="metric-value">{{ $formatCurrency($incomingOpenAmount) }} EUR</h3>
                            <p class="metric-subvalue">{{ $procurementStatus0 }} @lang('neplaćenih ulaznih faktura')</p>
                        </div>
                        <div class="invoice-icon bgl-warning">
                            <span><i class="flaticon-project fs-22 text-warning"></i></span>
                        </div>
                    </div>
                    <div class="metric-progress"><span style="width: {{ min($incomingUnpaidInvoiceRate, 100) }}%; background: #FFA825;"></span></div>
                    <p class="metric-subvalue">{{ $incomingUnpaidInvoiceRate }}% @lang('ulaznih faktura nije plaćeno')</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-topline">
                        <div>
                            <div class="metric-label">@lang('Ulazne plaćene')</div>
                            <h3 class="metric-value">{{ $formatCurrency($incomingPaidAmount) }} EUR</h3>
                            <p class="metric-subvalue">{{ $procurementStatus1 }} @lang('plaćenih ulaznih faktura')</p>
                        </div>
                        <div class="invoice-icon bgl-info">
                            <span><i class="flaticon-project fs-22 text-info"></i></span>
                        </div>
                    </div>
                    <div class="metric-progress"><span style="width: {{ min($incomingPaidInvoiceRate, 100) }}%; background: #40BAD5;"></span></div>
                    <p class="metric-subvalue">{{ $incomingPaidInvoiceRate }}% @lang('ulaznih faktura je plaćeno')</p>
                </div>
            </div>
        </div>
        
    </div>

    <div class="row">
        <div class="col-xl-8 prvi-grafikon">
            <div class="card">
                <div class="card-header border-0 flex-wrap pb-0">
                    <div>
                        <h4 class="card-title">@lang('Plaćene fakture po mesecima')</h4>
                        <span>@lang('Zbir plaćenih izlaznih i ulaznih faktura u svakom mesecu.')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="cashflow-summary">
                        <div class="cashflow-summary-item">
                            <span><i style="background:#2BC155;"></i>@lang('Izlazne plaćene')</span>
                            <strong>{{ $formatCurrency($outgoingPaidAmount) }} EUR</strong>
                        </div>
                        <div class="cashflow-summary-item">
                            <span><i style="background:#FFC107;"></i>@lang('Ulazne plaćene')</span>
                            <strong>{{ $formatCurrency($incomingPaidAmount) }} EUR</strong>
                        </div>
                    </div>
                    <div class="dashboard-chart">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('Otvorene obaveze i potraživanja')</h4>
                        <span>@lang('Pregled iznosa koje treba naplatiti i obaveza za plaćanje.')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart dashboard-chart-sm">
                        <canvas id="openPositionsChart"></canvas>
                    </div>
                    <table class="table summary-table mt-3">
                        <tbody>
                            <tr>
                                <hr>
                            </tr>
                            <tr>
                                <td>@lang('Otvorena potraživanja')</td>
                                <td class="text-danger">{{ $formatCurrency($outgoingOpenAmount) }} EUR</td>
                            </tr>
                            <tr>
                                <td>@lang('Otvorene obaveze')</td>
                                <td class="text-warning">{{ $formatCurrency($incomingOpenAmount) }} EUR</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block d-sm-flex border-0 flex-wrap transactions-tab">
                    <div class="me-3 mb-3">
                        <h4 class="card-title">@lang('Dospela potraživanja')</h4>
                        <span>@lang('Neplaćene izlazne fakture po izabranom datumu.')</span>
                    </div>
                    <div class="dashboard-date mt-3 mt-sm-0 mb-3">
                        <button class="btn btn-primary light" id="prev-date" type="button" aria-label="@lang('Prethodni dan')">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <input type="text" class="form-control text-center" name="date_start" id="date_start" value="">
                        <button class="btn btn-primary light" id="next-date" type="button" aria-label="@lang('Sledeći dan')">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-responsive-md card-table transactions-table" id="entity-list-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Firma')</th>
                                    <th class="text-end">@lang('Iznos')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block d-sm-flex border-0 flex-wrap transactions-tab">
                    <div class="me-3 mb-3">
                        <h4 class="card-title">@lang('Dospela plaćanja')</h4>
                        <span>@lang('Neplaćene ulazne fakture po izabranom datumu.')</span>
                    </div>
                    <div class="dashboard-date mt-3 mt-sm-0 mb-3">
                        <button class="btn btn-primary light" id="prev-date1" type="button" aria-label="@lang('Prethodni dan')">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <input type="text" class="form-control text-center" name="date_start1" id="date_start1" value="">
                        <button class="btn btn-primary light" id="next-date1" type="button" aria-label="@lang('Sledeći dan')">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-responsive-md card-table transactions-table" id="entity-list-table1" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Firma')</th>
                                    <th class="text-end">@lang('Iznos')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('Status izlaznih faktura po mesecima')</h4>
                        <span>@lang('Plaćene i neplaćene izlazne fakture po mesecima.')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart">
                        <canvas id="monthlyInvoicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('Kontrolna tabla')</h4>
                        <span>@lang('Najvažnije akcije i brzi finansijski rezime.')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="quick-actions mb-4">
                        <a href="{{ route('customer-invoices.index') }}" class="btn btn-primary light">
                            <i class="fa fa-file-invoice-dollar"></i>@lang('Izlazne fakture')
                        </a>
                        <a href="{{ route('supplier-invoices.index') }}" class="btn btn-primary light">
                            <i class="fa fa-file-lines"></i>@lang('Ulazne fakture')
                        </a>
                        <a href="{{ route('firme.index') }}" class="btn btn-primary light">
                            <i class="fa fa-building"></i>@lang('Firme')
                        </a>
                        <a href="{{ route('supplier-invoices.reports') }}" class="btn btn-primary">
                            <i class="fa fa-chart-simple"></i>@lang('Ulazni izveštaji')
                        </a>
                    </div>

                    <table class="table summary-table">
                        <tbody>
                            <tr>
                                <td>@lang('Ukupno izlazno')</td>
                                <td>{{ $formatCurrency($totalReceivables) }} EUR</td>
                            </tr>
                            <tr>
                                <td>@lang('Ukupno ulazno')</td>
                                <td>{{ $formatCurrency($totalPayables) }} EUR</td>
                            </tr>
                            <tr>
                                <td>@lang('Naplaćeno izlazno')</td>
                                <td class="text-success">{{ $formatCurrency($outgoingPaidAmount) }} EUR</td>
                            </tr>
                            <tr>
                                <td>@lang('Plaćeno ulazno')</td>
                                <td class="text-info">{{ $formatCurrency($incomingPaidAmount) }} EUR</td>
                            </tr>
                            <tr>
                                <td>@lang('Razlika između naplaćenih i plaćenih')</td>
                                <td class="{{ $netPaidCashflow >= 0 ? 'text-success' : 'text-danger' }}">{{ $formatCurrency($netPaidCashflow) }} EUR</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="card-title">@lang('Status ulaznih faktura po mesecima')</h4>
                        <span>@lang('Plaćene i neplaćene ulazne fakture po mesecima.')</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dashboard-chart">
                        <canvas id="monthlyProcurementsChart"></canvas>
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
    const monthlyInvoicesData = @json($monthlyInvoices);
    const monthlyProcurementsData = @json($monthlyProcurements);
    const monthlyInvoicesNotPaid = @json($monthlyInvoicesNotPaid);
    const monthlyProcurementsNotPaid = @json($monthlyProcurementsNotPaid);
    const openReceivables = @json($outgoingOpenAmount);
    const openPayables = @json($incomingOpenAmount);
    const months = ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];

    function prepareData(data) {
        let result = new Array(12).fill(0);

        data.forEach(item => {
            let monthIndex = Number(item.month) - 1;
            result[monthIndex] = Number.parseFloat(item.total || 0);
        });

        return result;
    }

    function moneyTick(value) {
        return new Intl.NumberFormat('de-DE', {
            maximumFractionDigits: 0
        }).format(value) + ' EUR';
    }

    const paidInvoices = prepareData(monthlyInvoicesData);
    const paidProcurements = prepareData(monthlyProcurementsData);
    const unpaidInvoices = prepareData(monthlyInvoicesNotPaid);
    const unpaidProcurements = prepareData(monthlyProcurementsNotPaid);
    const netCashflow = paidInvoices.map((value, index) => value - paidProcurements[index]);
    const chartGridColor = 'rgba(35, 28, 58, 0.06)';

    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + moneyTick(context.parsed.y ?? context.parsed);
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: moneyTick
                }
            }
        }
    };

    const paidInvoicesFlowOptions = {
        ...commonChartOptions,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            ...commonChartOptions.plugins,
            legend: {
                position: 'bottom',
                align: 'end',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8,
                    padding: 18
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#7e7e7e'
                }
            },
            y: {
                beginAtZero: true,
                border: {
                    display: false
                },
                grid: {
                    color: chartGridColor
                },
                ticks: {
                    color: '#7e7e7e',
                    padding: 10,
                    callback: moneyTick
                }
            }
        }
    };

    new Chart(document.getElementById('cashflowChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: ' @lang('Izlazne plaćene')',
                    data: paidInvoices,
                    backgroundColor: 'rgba(43, 193, 85, 0.78)',
                    borderColor: '#2BC155',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                    categoryPercentage: 0.62,
                    barPercentage: 0.72,
                    maxBarThickness: 30
                },
                {
                    label: ' @lang('Ulazne plaćene')',
                    data: paidProcurements,
                    backgroundColor: 'rgba(255, 193, 7, 0.78)',
                    borderColor: '#FFC107',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                    categoryPercentage: 0.62,
                    barPercentage: 0.72,
                    maxBarThickness: 30
                },
                {
                    label: ' @lang('Linija izlaznih plaćenih')',
                    data: paidInvoices,
                    type: 'line',
                    borderColor: '#168f3b',
                    backgroundColor: '#168f3b',
                    borderWidth: 3,
                    tension: 0.35,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHitRadius: 14,
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round',
                    order: 0
                },
                {
                    label: ' @lang('Linija ulaznih plaćenih')',
                    data: paidProcurements,
                    type: 'line',
                    borderColor: '#FFC107',
                    backgroundColor: '#FFC107',
                    borderWidth: 3,
                    tension: 0.35,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHitRadius: 14,
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round',
                    order: 0
                }
            ]
        },
        options: paidInvoicesFlowOptions
    });

    new Chart(document.getElementById('openPositionsChart'), {
        type: 'doughnut',
        data: {
            labels: [' @lang('Potraživanja')', ' @lang('Obaveze')'],
            datasets: [{
                data: [openReceivables, openPayables],
                backgroundColor: ['var(--primary)', '#FF827A'],
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
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + moneyTick(context.parsed);
                        }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('monthlyInvoicesChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: '@lang('Plaćeno')',
                    data: paidInvoices,
                    backgroundColor: 'rgba(43, 193, 85, 0.78)',
                    borderRadius: 6
                },
                {
                    label: '@lang('Neplaćeno')',
                    data: unpaidInvoices,
                    backgroundColor: 'rgba(253, 83, 83, 0.72)',
                    borderRadius: 6
                }
            ]
        },
        options: commonChartOptions
    });

    new Chart(document.getElementById('monthlyProcurementsChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: '@lang('Plaćeno')',
                    data: paidProcurements,
                    backgroundColor: 'rgba(64, 186, 213, 0.72)',
                    borderRadius: 6
                },
                {
                    label: '@lang('Neplaćeno')',
                    data: unpaidProcurements,
                    backgroundColor: 'rgba(255, 168, 37, 0.72)',
                    borderRadius: 6
                }
            ]
        },
        options: commonChartOptions
    });
});

const dateInput = document.getElementById('date_start');
const dateInput1 = document.getElementById('date_start1');
const today = new Date();
dateInput.value = formatDate(today);
dateInput1.value = formatDate(today);

var blade = {
    datatablesAjaxCustomer: "{{ route('datatable_customers') }}",
    datatablesAjaxSupplier: "{{ route('datatable_suppliers') }}",
};

function formatDate(date) {
    const day = ("0" + date.getDate()).slice(-2);
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

function readPickerDate(input) {
    return new Date(input.value.split('-').reverse().join('-'));
}

document.getElementById('next-date').addEventListener('click', function() {
    const currentDate = readPickerDate(dateInput);
    currentDate.setDate(currentDate.getDate() + 1);
    dateInput.value = formatDate(currentDate);
    table.ajax.reload();
});

document.getElementById('prev-date').addEventListener('click', function() {
    const currentDate = readPickerDate(dateInput);
    currentDate.setDate(currentDate.getDate() - 1);
    dateInput.value = formatDate(currentDate);
    table.ajax.reload();
});

document.getElementById('next-date1').addEventListener('click', function() {
    const currentDate = readPickerDate(dateInput1);
    currentDate.setDate(currentDate.getDate() + 1);
    dateInput1.value = formatDate(currentDate);
    table1.ajax.reload();
});

document.getElementById('prev-date1').addEventListener('click', function() {
    const currentDate = readPickerDate(dateInput1);
    currentDate.setDate(currentDate.getDate() - 1);
    dateInput1.value = formatDate(currentDate);
    table1.ajax.reload();
});

var table = $('#entity-list-table').DataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    paging: false,
    info: false,
    searching: false,
    language: {
        emptyTable: "@lang('Nema dospelih izlaznih faktura za izabrani datum.')",
        processing: "@lang('Učitavanje...')"
    },
    ajax: {
        url: blade.datatablesAjaxCustomer,
        type: "post",
        data: function(d) {
            d.date_start = $('#date_start').val();
        }
    },
    columns: [
        {"data": "id_invoice", orderable: false},
        {"data": "company", orderable: false},
        {"data": "price", orderable: false, "className": "text-end"},
    ],
    order: [[0, "asc"]],
});

var table1 = $('#entity-list-table1').DataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    paging: false,
    info: false,
    searching: false,
    language: {
        emptyTable: "@lang('Nema dospelih ulaznih faktura za izabrani datum.')",
        processing: "@lang('Učitavanje...')"
    },
    ajax: {
        url: blade.datatablesAjaxSupplier,
        type: "post",
        data: function(d) {
            d.date_start1 = $('#date_start1').val();
        }
    },
    columns: [
        {"data": "id_invoice", orderable: false},
        {"data": "company", orderable: false},
        {"data": "price", orderable: false, "className": "text-end"},
    ],
    order: [[0, "asc"]],
});
</script>
@endpush
