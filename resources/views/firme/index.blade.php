@extends('_layouts.layout')

@section('head_title', __('Firme'))

@push('head_links')

@endpush

@section('content')

<style>
	.companies-page .card-header {
		gap: 12px;
	}

	.companies-page .company-index-table {
		width: 100% !important;
	}

	.companies-page .company-index-table td,
	.companies-page .company-index-table th {
		vertical-align: middle;
	}

	.companies-page .company-index-table td {
		white-space: nowrap;
	}

	.companies-page .company-index-table td:nth-child(1),
	.companies-page .company-index-table td:nth-child(2),
	.companies-page .company-index-table td:nth-child(6) {
		white-space: normal;
	}

	@media (max-width: 767px) {
		.companies-page .card-header {
			display: block;
		}

		.companies-page .card-header .btn {
			width: 100%;
			margin-top: 12px;
		}

		.companies-page .card-body {
			padding: 16px;
		}

		.companies-page .table-responsive {
			overflow: visible;
		}

		.companies-page .dataTables_wrapper .dataTables_length,
		.companies-page .dataTables_wrapper .dataTables_filter,
		.companies-page .dataTables_wrapper .dataTables_info,
		.companies-page .dataTables_wrapper .dataTables_paginate {
			float: none;
			text-align: left;
			width: 100%;
		}

		.companies-page .dataTables_wrapper .dataTables_filter {
			margin-top: 10px;
		}

		.companies-page .dataTables_wrapper .dataTables_filter label {
			display: flex;
			align-items: center;
			gap: 8px;
			width: 100%;
			margin-bottom: 0;
			white-space: nowrap;
		}

		.companies-page .dataTables_wrapper .dataTables_filter input {
			flex: 1 1 auto;
			min-width: 0;
			width: auto;
			margin: 0;
		}

		.companies-page .company-index-table,
		.companies-page .company-index-table tbody,
		.companies-page .company-index-table tr,
		.companies-page .company-index-table td {
			display: block;
			width: 100% !important;
		}

		.companies-page .company-index-table thead {
			display: none;
		}

		.companies-page .company-index-table tr {
			border: 1px solid #eef1f7;
			border-radius: 8px;
			padding: 12px;
			margin-bottom: 12px;
			background: #fff;
		}

		.companies-page .company-index-table td {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			gap: 14px;
			border: 0;
			padding: 7px 0;
			white-space: normal;
			text-align: right !important;
			overflow-wrap: anywhere;
		}

		.companies-page .company-index-table td:before {
			content: attr(data-label);
			color: #7e7e7e;
			font-size: 12px;
			font-weight: 700;
			text-align: left;
			min-width: 96px;
		}

		.companies-page .company-index-table td:first-child {
			display: block;
			text-align: left !important;
			font-size: 16px;
			font-weight: 700;
		}

		.companies-page .company-index-table td:first-child:before {
			display: block;
			margin-bottom: 5px;
			font-size: 11px;
			font-weight: 700;
		}

		.companies-page .company-index-table td:last-child {
			justify-content: flex-start;
			padding-top: 12px;
		}

		.companies-page .company-index-table td:last-child:before {
			display: none;
		}

		.companies-page .company-index-table .btn-group {
			width: 100%;
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 8px;
		}

		.companies-page .company-index-table .btn-group .btn {
			width: 100%;
			border-radius: 6px !important;
		}

		@media (max-width: 767px) {
			.dataTables_length {
				display: none;
			}
		}
	}
</style>

<div class="companies-page">
<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Pregled')</li>
	</ol>
</div>

<!-- row -->
<div class="row">
	
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">@lang('Lista firmi')</h4>
                
                <a href="{{route('firme.create')}}" class="btn btn-primary">
					<i class="fa fa-plus"></i>
					@lang('Dodaj')
				</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="exampledb" class="display company-index-table">
						<thead>
							<tr>
								<th class="">@lang('Ime firme')</th>
								<th class="">@lang('Adresa')</th>
								<th class="">@lang('Ort')</th>
								<th class="">@lang('UID-Nummer')</th>
								<th class="">@lang('Telefon')</th>
								<th class="">@lang('Email')</th>
								<th class="disabled-sorting text-right">@lang('Opcije')</th>
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
@endsection

@push('footer_scripts')
<script>
$(function() {
	
	var blade = {
		datatablesAjaxUrl:"{{ route('firme.datatable') }}"
	};
	var tableLabels = [
		@json(__('Ime firme')),
		@json(__('Adresa')),
		@json(__('Ort')),
		@json(__('UID-Nummer')),
		@json(__('Telefon')),
		@json(__('Email')),
		@json(__('Opcije'))
	];
	
	// DATATABLES
	$('#exampledb').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: blade.datatablesAjaxUrl,
			type: "post",
		},
		"columns": [
			{"data": "name", "className": ""},
			{"data": "address", "className": ""},
			{"data": "ort", "className": ""},
			{"data": "uid", "className": ""},
			{"data": "phone", "className": ""},
			{"data": "email", "className": ""},
			{"data": "actions", orderable: false, searchable: false, "className": "text-right"}
		],
		"createdRow": function(row) {
			$('td', row).each(function(index) {
				$(this).attr('data-label', tableLabels[index] || '');
			});
		},
		
		"language": {
			"search": "Suchen:",
			"info": "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
			"infoEmpty": "Keine Einträge verfügbar",
			"infoFiltered": "(gefiltert von _MAX_ gesamten Einträgen)",
			"lengthMenu": "Zeige _MENU_ Einträge",
			"zeroRecords": "Keine passenden Einträge gefunden",
			"paginate": {
				"previous": '<i class="fa-solid fa-angle-left"></i>', // Strelica levo
				"next": '<i class="fa-solid fa-angle-right"></i>' // Strelica desno
			},
			// "lengthMenu": `
            //     <div class="dropdown">
            //         <select name="exampledb_length" aria-controls="example" 
            //                 class="form-select custom-dropdown">
            //             <option value="10">10</option>
            //             <option value="25">25</option>
            //             <option value="50">50</option>
            //             <option value="100">100</option>
            //         </select>
            //     </div>`
		},
		"drawCallback": function(settings) {
			var api = this.api();
			var totalPages = api.page.info().pages;
			if (totalPages <= 1) {
				// Sakrij paginaciju ako ima samo jedna stranica
				$(api.table().container()).find('.dataTables_paginate').hide();
			} else {
				$(api.table().container()).find('.dataTables_paginate').show();
			}
		},
		"order": [[0, "asc"]]
	});

});

</script>
@endpush
