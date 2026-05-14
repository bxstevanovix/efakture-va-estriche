@extends('_layouts.layout')

@section('head_title', __('Izveštaj') . ' - ' . __('Ulazne Fakture'))

@push('head_links')

@endpush

@section('content')

<style>
    #exampledb thead th:first-child.sorting::after,
    #exampledb thead th:first-child.sorting_asc::after,
    #exampledb thead th:first-child.sorting_desc::after {
        display: bloc;
    }

    #exampledb {
        margin-bottom: 30px;
    }
    
    #exampledb tbody td {
        padding: 20px;
    }

    #exampledb th:nth-child(2),
    #exampledb td:nth-child(2) {
        max-width: 600px; 
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }

    #exampledb th:nth-child(3),
    #exampledb td:nth-child(3) {
        max-width: 600px; 
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }

    th, td {
        white-space: nowrap;
    }

    .dataTables_filter {
        display: none;
    }
    
    .date-highlight {
        padding: 5px;
        border-radius: 5px;
        display: inline-block;
    }
    .date-red {
        background-color: red;
        color: white;
    }
    .date-yellow {
        background-color: yellow;
        color: black;
    }
    .date-green {
        background-color: green;
        color: white;
    }
    .summary-box {
        padding: 16px 18px;
        border-radius: 8px;
        text-align: center;
        font-size: 17px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
        border: 1px solid #eef1f7;
        margin-bottom: 18px;
    }

    .summary-box div {
        white-space: nowrap;
    }

    .summary-box a {
        color: inherit;
        font-size: 14px;
        font-weight: 400;
        text-transform: uppercase;
    }

    .summary-box span {
        font-size: 18px;
        font-weight: 800;
    }

    .summary-eur {
        background-color: white;
    }
    .summary-km {
        background-color: white;
    }
    .summary-rsd {
        background-color: white;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
        float: right;
        height: auto;
    }

    .dataTables_length {
        margin-bottom: 10px;
    }

    @media (max-width: 767px) {
        .summary-box {
            padding: 13px 14px;
            font-size: 16px;
        }

        .summary-box a {
            font-size: 13px;
        }

        .summary-box span {
            font-size: 16px;
            font-weight: 700;
        }
    }
</style>

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('supplier-invoices.index')}}">@lang('Ulazne fakture')</a></li>
		<li class="breadcrumb-item active">@lang('Pregled')</li>
	</ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="filter cm-content-box box-primary">
            <div style="display: none;" class="content-title SlideToolHeader">
            </div>
            <div class="cm-content-body form excerpt">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-2 col-sm-6">
                            <label class="form-label">Date</label>
                            <div class="input-hasicon mb-sm-0 mb-3">
                                <input
                                    type="text"
                                    class="form-control text-center"
                                    name="date_start"
                                    id="date_start"
                                    value=""
                                >
                                <div class="icon"><i class="far fa-calendar"></i></div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-sm-6">
                            <label class="form-label">Date</label>
                            <div class="input-hasicon mb-sm-0 mb-3">
                                <input
                                    type="text"
                                    class="form-control text-center "
                                    name="date_end"
                                    id="date_end"
                                    value=""
                                >
                                <div class="icon"><i class="far fa-calendar"></i></div>
                            </div>
                        </div>
                        <div class="col-xl-3  col-sm-6 mb-3 mb-xl-0">
                            <label class="form-label">@lang('Filter po statusu'):</label>
                            <select id="status" class="form-control default-select h-auto wide"
                                aria-label="Default select example">
                                <option value="">@lang('Svi')</option>
                                <option value="1">@lang('Plaćeno')</option>
                                <option value="0">@lang('Neplaćeno')</option>
                            </select>
                        </div>
                        <div class="col-xl-5  col-sm-6 mb-3 mb-xl-0">
                            <label class="form-label">@lang('Firme')</label>
                            <select id="company" class="form-control default-select h-auto wide"
                                aria-label="Default select example">
                                <option value="">@lang('Sve firme')</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="exampledb" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="">@lang('Broj Fakture')</th>
                                        <th class="">@lang('Firma')</th>
                                        <th class="">@lang('Adresa')</th>
                                        <th class="">@lang('Datum kreiranja')</th>
                                        <th class="">@lang('Rok placanja')</th>
                                        <th class="">@lang('Iznos')</th>
                                        <th class="disabled-sorting text-right">@lang('')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="summary-box summary-eur">
            <div class="text-danger"><a>@lang('Ceka na uplatu')</a>: <span id="pending-eur">0</span> EUR</div>
        </div>
    </div>
    <div class="col-md-2">
        
    </div>
    <div class="col-md-5">
        <div class="summary-box summary-eur">
            <div class="text-success"><a>@lang('Bezahlt')</a>: <span id="paid-eur">0</span> EUR</div>
        </div>
    </div>
</div>

@endsection

@push('footer_scripts')
<script>
$(function() {

    var dateFormat = 'dd/mm/yyyy';

    var startOfYear = moment().startOf('year').format('DD-MM-YYYY');
    var today = moment().format('DD-MM-YYYY');
    $('#date_start').val(startOfYear);
    $('#date_end').val(today);

    $('#date_start').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#date_end').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    // DATATABLES
    var table = $('#exampledb').DataTable({
        "responsive": true,
        "autoWidth": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "{{ route('supplier-invoices.reports_datatable') }}",
            type: "post",
            data: function(d) {
                d.date_start = $('#date_start').val();
                d.date_end = $('#date_end').val();
                d.company = $('#company').val();
                d.status = $('#status').val();
            }
        },
        "columns": [
            {"data": "id_invoice", orderable: true, "className": "", widths: "10%"},
            {"data": "company", orderable: false, "className": "", width: "25%"},
            {"data": "address", orderable: false, searchable: false, "className": "", width: "25%"},
            {"data": "date_start", orderable: false, searchable: false, "className": "text-center", width: "10%"},
            {"data": "date_end", orderable: false, searchable: false, "className": "text-center", width: "10%"},
            {"data": "price", orderable: false, "className": "", width: "10%"},
            {"data": "actions", orderable: false, searchable: false, "className": "text-right", width: "10%"}
        ],
        "order": [[0, "asc"]],
        "rowCallback": function(row, data, index) {
            var today = moment();
            var dateEnd = moment(data.date_end, 'DD-MM-YYYY');

        },
        "drawCallback": function(settings) {
            var api = this.api();
            var sums = settings.json;

            var pendingEur = parseFloat(sums.sumEUR.pending);
            var paidEur = parseFloat(sums.sumEUR.paid);

            $('#pending-eur').text(pendingEur.toFixed(2));
            $('#paid-eur').text(paidEur.toFixed(2));

            var api = this.api();
			var totalPages = api.page.info().pages;
			if (totalPages <= 1) {
				// Sakrij paginaciju ako ima samo jedna stranica
				$(api.table().container()).find('.dataTables_paginate').hide();
			} else {
				$(api.table().container()).find('.dataTables_paginate').show();
			}
        },
        "language": {
            "search": "Suche:",
            "info": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
                "infoEmpty": "Keine Einträge verfügbar",
                "infoFiltered": "(gefiltert von _MAX_ gesamten Einträgen)",
                "lengthMenu": "Zeige _MENU_ Einträge",
                "zeroRecords": "Keine passenden Einträge gefunden",
			"paginate": {
				"previous": '<i class="fa-solid fa-angle-left"></i>', // Strelica levo
				"next": '<i class="fa-solid fa-angle-right"></i>' // Strelica desno
			}
		},
        "dom": '<"row"<"col-md-6"l><"col-md-6 text-end"B>>rtip',
        "buttons": [
            {
                extend: 'pdfHtml5',
                text: '<i class="fa-solid fa-file-pdf"></i> Export PDF',
                className: 'btn btn-sm bg-primary text-white border-0 shadow-none rounded',
                customize: function(doc) {
                    // Uklanjanje prethodnog sadržaja
                    doc.content = [];

                    // Prilagođeni naslov
                    doc.content.push({
                        text: 'Rechnungsbericht',
                        fontSize: 16,
                        alignment: 'center',
                        margin: [0, 0, 0, 20]
                    });

                    // Dodavanje filtera
                    doc.content.push({
                        margin: [0, 0, 0, 20],
                        table: {
                            widths: ['*', '*', '*', '*'],
                            body: [
                                [
                                    { text: 'Datum von', bold: true },
                                    { text: 'Datum bis', bold: true },
                                    { text: 'Status', bold: true },
                                    { text: 'Firma', bold: true }
                                ],
                                [
                                    $('#date_start').val() || '- - -',
                                    $('#date_end').val() || '- - -',
                                    $('#status option:selected').text() || 'N/A',
                                    $('#company option:selected').text() || 'N/A'
                                ]
                            ]
                        },
                        layout: 'lightHorizontalLines'
                    });

                    // Dodavanje podataka iz tabele
                    var tableData = $('#exampledb').DataTable().rows().data().toArray();

                    var tableBody = tableData.map(function(row) {
                        // Prilagođavanje poslednje kolone za status
                        var statusSymbol = row.invoice_status == 1 ? '✔ Otkaceno' : '✘ Neplaceno';

                        return [
                            row.id_invoice || '-',
                            row.company || '-',
                            row.address || '-',
                            // row.square_meters || '-',
                            row.date_end || '-',
                            row.price ? row.price + ' €' : '-'
                        ];
                    });

                    doc.content.push({
                        margin: [0, 10, 0, 20],
                        table: {
                            widths: [50, 100, 170, 60, 75],
                            body: [
                                [
                                    { text: 'ID', bold: true },
                                    { text: 'Firma', bold: true },
                                    { text: 'Adresse', bold: true },
                                    { text: 'Zahlungsfrist', bold: true },
                                    { text: 'Preis', bold: true },
                                ],
                                ...tableBody
                            ]
                        },
                        layout: 'lightHorizontalLines'
                    });

                    // Footer sa trenutnim datumom
                    doc.footer = function(currentPage, pageCount) {
                        var currentDate = new Date().toLocaleDateString();
                        return {
                            text: 'Seite ' + currentPage + ' von ' + pageCount + ' | Datum: ' + currentDate,
                            alignment: 'center',
                            fontSize: 10
                        };
                    };
                }
            },
            {
                extend: 'csv',
                text: '<i class="fa-solid fa-file-csv"></i> CSV Report',
                className: 'btn btn-sm bg-primary text-white border-0 shadow-none rounded'
            }
        ]


    });

    $('#date_start, #date_end, #company, #status').change(function() {
        table.ajax.reload();
    });

    $('#generate-pdf').on('click', function() {
        var filteredData = table.rows({ filter: 'applied' }).data().toArray();

        $.ajax({
            url: "{{ route('supplier-invoices.generate_pdf') }}",
            type: 'POST',
            data: {
                data: filteredData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                var link = document.createElement('a');
                link.href = response.url;
                link.download = 'tabela.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.error('Greška prilikom generisanja PDF-a:', error);
            }
        });
    });

});
</script>
@endpush
