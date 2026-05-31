	@extends('_layouts.layout')

	@section('head_title', __('Racuni'))

	@push('head_links')
	@endpush

	@section('content')

	@include('make_rechnung.partials.style')

	<style>
		.rechnung-page .card-header {
			gap: 12px;
		}

		.rechnung-page .card-body {
			padding: 20px !important;
			padding-bottom: 30px !important;
		}

		.rechnung-page .rechnung-index-table {
			width: 100% !important;
		}

		.rechnung-page .rechnung-index-table th,
		.rechnung-page .rechnung-index-table td {
			white-space: nowrap;
			vertical-align: middle;
		}

		.rechnung-page #openModal {
			float: none;
		}

		.rechnung-page button {
			float: none;
		}

		.rechnung-page .rechnung-create-text {
			margin-left: 4px;
		}

		.rechnung-page .btn-group {
			display: inline-flex;
			gap: 8px;
			max-height: none !important;
		}

		.rechnung-page .btn-group .btn {
			min-width: 44px;
			border-radius: 6px !important;
		}

		@media (max-width: 767px) {
			.rechnung-page .card-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}

			.rechnung-page .card-header .btn {
				width: 42px;
				height: 42px;
				min-width: 42px;
				padding: 0;
				display: inline-flex;
				align-items: center;
				justify-content: center;
			}

			.rechnung-page .rechnung-create-text {
				display: none;
			}

			.rechnung-page .card-body {
				padding: 16px !important;
			}

			.rechnung-page .dataTables_length {
				display: none;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter,
			.rechnung-page .dataTables_wrapper .dataTables_info,
			.rechnung-page .dataTables_wrapper .dataTables_paginate {
				float: none;
				text-align: left;
				width: 100%;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter {
				margin-top: 10px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter label {
				display: flex;
				align-items: center;
				gap: 8px;
				width: 100%;
				margin-bottom: 0;
				white-space: nowrap;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter input {
				flex: 1 1 auto;
				min-width: 0;
				width: auto;
				margin: 0;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate {
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 6px;
				margin-top: 14px;
				white-space: nowrap;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate span {
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate .paginate_button {
				min-width: 34px;
				height: 34px;
				padding: 0 !important;
				margin: 0 !important;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 6px !important;
				font-size: 13px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate span .paginate_button:not(.current):not(:first-child):not(:last-child),
			.rechnung-page .dataTables_wrapper .dataTables_paginate .ellipsis {
				display: none !important;
			}

			.rechnung-page .table-responsive {
				overflow: visible;
			}

			.rechnung-page .rechnung-index-table,
			.rechnung-page .rechnung-index-table tbody,
			.rechnung-page .rechnung-index-table tr,
			.rechnung-page .rechnung-index-table td {
				display: block;
				width: 100% !important;
			}

			.rechnung-page .rechnung-index-table thead {
				display: none;
			}

			.rechnung-page .rechnung-index-table tr {
				display: grid;
				grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
				column-gap: 12px;
				row-gap: 0;
				border: 1px solid #eef1f7;
				border-radius: 8px;
				padding: 12px;
				margin-bottom: 12px;
				background: #fff;
			}

			.rechnung-page .rechnung-index-table td {
				border: 0 !important;
				box-shadow: none !important;
				max-width: none !important;
				overflow: visible !important;
				padding: 0;
				white-space: normal;
				text-align: left !important;
				overflow-wrap: anywhere;
			}

			.rechnung-page .rechnung-index-table td:before {
				display: none;
			}

			.rechnung-page .rechnung-index-table td:first-child {
				grid-column: 1;
				grid-row: 1;
				font-size: 16px;
				font-weight: 700;
				color: #1f2937;
				line-height: 1.2;
			}

			.rechnung-page .rechnung-index-table td:nth-child(6) {
				grid-column: 2;
				grid-row: 1;
				align-self: start;
				justify-self: end;
				font-size: 16px;
				font-weight: 800;
				color: #1f2937;
				text-align: right !important;
				white-space: nowrap;
			}

			.rechnung-page .rechnung-index-table td:nth-child(2) {
				grid-column: 1 / -1;
				grid-row: 2;
				margin-top: 10px;
				font-size: 14px;
				font-weight: 700;
			}

			.rechnung-page .rechnung-index-table td:nth-child(3) {
				grid-column: 1 / -1;
				grid-row: 3;
				margin-top: 4px;
				color: #7e7e7e;
				font-size: 13px;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4),
			.rechnung-page .rechnung-index-table td:nth-child(5) {
				grid-row: 4;
				margin-top: 12px;
				padding: 8px 10px;
				border-radius: 6px;
				background: #f6f7fb;
				font-size: 12px;
				font-weight: 700;
				color: #3f4b5b;
				min-width: 0;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4) {
				grid-column: 1;
			}

			.rechnung-page .rechnung-index-table td:nth-child(5) {
				grid-column: 2;
				text-align: left !important;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4):before,
			.rechnung-page .rechnung-index-table td:nth-child(5):before {
				display: block;
				content: attr(data-label);
				margin-bottom: 4px;
				color: #9aa1ad;
				font-size: 10px;
				font-weight: 700;
				text-transform: uppercase;
			}

			.rechnung-page .rechnung-index-table td:nth-child(7) {
				grid-column: 1 / -1;
				grid-row: 5;
				margin-top: 12px;
			}

			.rechnung-page .rechnung-index-table .btn-group {
				width: 100%;
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 8px;
			}

			.rechnung-page .rechnung-index-table .btn-group .btn {
				width: 100%;
				min-height: 42px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 6px !important;
			}
		}
	</style>

	<div class="rechnung-page">
	<div class="row page-titles mx-0">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('rechnung.index')}}">@lang('Racuni')</a></li>
			<li class="breadcrumb-item active">@lang('Pregled')</li>
		</ol>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">@lang('Racuni')</h4>
					<button 
						id="openModal"
						type="button"
						class="btn btn-primary"
						title="@lang('Kreiraj racun')"
					>
						<i class="fa fa-plus"></i><span class="rechnung-create-text">@lang('Kreiraj racun')</span>
					</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="exampledb" class="display w-100 rechnung-index-table">
							<thead>
								<tr>
									<th>@lang('ID')</th>
									<th>@lang('Firma')</th>
									<th>@lang('Adresa')</th>
									<th>@lang('Datum')</th>
									<th>@lang('Kreirao')</th>
									<th>@lang('Cijena')</th>
									<th class="text-center">@lang('Opcije')</th>
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

	@include('make_rechnung.partials.modal')
	@include('make_rechnung.partials.customer_invoice_modal')
@endsection

@push('footer_scripts')
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
	<script>
		$(function() {
			const quill = new Quill('#editor', {
				theme: 'snow',
				placeholder: 'Optionaler Text...',
				modules: {
					toolbar: [
						['bold', 'italic', 'underline'],
						[{ 'size': ['small', false, 'large', 'huge'] }],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],
						['clean']
					]
				}
			});

			const documentLabels = {
				rechnung: 'Rechnung',
				teilrechnung: 'Teilrechnung',
				schlussrechnung: 'Schlussrechnung'
			};

			const datatableUrl = "{{ route('rechnung.datatable') }}";
			const tableLabels = [
				@json(__('ID')),
				@json(__('Firma')),
				@json(__('Adresa')),
				@json(__('Datum')),
				@json(__('Kreirao')),
				@json(__('Cijena')),
				@json(__('Opcije'))
			];

			$('#exampledb').DataTable({
				processing: true,
				serverSide: true,
				responsive: false,
				autoWidth: false,
				ajax: { url: datatableUrl, type: "post" },
				columns: [
					{ data: "id_invoice", name: "id_invoice", width: "10%" },
					{ data: "firma", width: "22%", orderable: false },
					{ data: "adress", width: "22%", orderable: false },
					{ data: "date_start", width: "10%", orderable: false },
					{ data: "created_by", width: "16%", orderable: false },
					{ data: "price", width: "10%", orderable: false },
					{ data: "actions", width: "10%", orderable: false, searchable: false, className: "text-center" }
				],
				language: {
					search: "Suchen:",
					paginate: {
						previous: '<i class="fa-solid fa-angle-left"></i>',
						next: '<i class="fa-solid fa-angle-right"></i>'
					}
				},
				order: [[0, "desc"]],
				createdRow: function(row) {
					$('td', row).each(function(index) {
						$(this).attr('data-label', tableLabels[index] || '');
					});
				},
				drawCallback: function() {
					const api = this.api();
					const pageInfo = api.page.info();
					const paginate = $(api.table().container()).find('.dataTables_paginate');

					if (pageInfo.pages <= 1) {
						paginate.hide();
					} else {
						paginate.show();
					}
				}
			});

			const sendToCustomerInvoiceModalElement = document.getElementById('sendToCustomerInvoiceModal');
			const sendToCustomerInvoiceModal = sendToCustomerInvoiceModalElement && window.bootstrap
				? new bootstrap.Modal(sendToCustomerInvoiceModalElement)
				: null;
			const transferLoadError = @json(__('Podaci za račun nisu mogli biti učitani.'));

			document.addEventListener('click', function(event) {
				const button = event.target.closest('.send-to-customer-invoice');

				if (!button) {
					return;
				}

				event.preventDefault();

				const originalHtml = button.innerHTML;
				button.disabled = true;
				button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

				fetch(button.dataset.url, {
					headers: {
						'Accept': 'application/json'
					}
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Transfer data could not be loaded.');
						}

						return response.json();
					})
					.then(data => {
						fillCustomerInvoiceModal(data);

						if (sendToCustomerInvoiceModal) {
							sendToCustomerInvoiceModal.show();
						} else {
							$('#sendToCustomerInvoiceModal').modal('show');
						}
					})
					.catch(() => {
						if (window.toastr) {
							toastr.error(transferLoadError);
						} else {
							alert(transferLoadError);
						}
					})
					.finally(() => {
						button.disabled = false;
						button.innerHTML = originalHtml;
					});
			});

			function fillCustomerInvoiceModal(data) {
				const form = document.getElementById('entity-form');
				const companyWarning = document.getElementById('transferCompanyWarning');
				const pdfWarning = document.getElementById('transferPdfWarning');

				if (!form) {
					return;
				}

				form.reset();
				clearTransferReadonlyFields();
				setTransferField('source_rechnung_id', data.id);
				setTransferFieldByName('id_invoice', data.id_invoice);
				setTransferFieldByName('address', data.address);
				setTransferDateField('date_start', data.date_start);
				setTransferDateField('date_end', '');
				setTransferFieldByName('price', data.price);
				setTransferFieldByName('text', data.text);
				lockTransferReadonlyFields();
				$('#companySelect').val(data.company || '').trigger('change');
				lockCompanyField(data.company);
				companyWarning?.classList.toggle('d-none', Boolean(data.company));
				pdfWarning?.classList.toggle('d-none', Boolean(data.pdf_available));
			}

			function lockTransferReadonlyFields() {
				[
					'id_invoice',
					'date_start',
					'price'
				].forEach(name => {
					const field = document.querySelector(`#entity-form [name="${name}"]`);

					if (!field) {
						return;
					}

					field.readOnly = true;
					field.classList.remove('bg-light');
					field.classList.add('transfer-readonly');
				});
			}

			function clearTransferReadonlyFields() {
				document.querySelectorAll('#entity-form .transfer-readonly').forEach(field => {
					field.readOnly = false;
					field.classList.remove('transfer-readonly');
				});

				const companySelect = $('#companySelect');
				companySelect.prop('disabled', false).trigger('change');
				document.querySelector('#entity-form input[data-transfer-company="1"]')?.remove();
			}

			function lockCompanyField(companyId) {
				const form = document.getElementById('entity-form');
				const companySelect = $('#companySelect');
				let hiddenCompany = form?.querySelector('input[data-transfer-company="1"]');

				if (!form || !companySelect.length) {
					return;
				}

				if (companyId) {
					if (!hiddenCompany) {
						hiddenCompany = document.createElement('input');
						hiddenCompany.type = 'hidden';
						hiddenCompany.name = 'company';
						hiddenCompany.dataset.transferCompany = '1';
						form.appendChild(hiddenCompany);
					}

					hiddenCompany.value = companyId;
					companySelect.prop('disabled', true).trigger('change');
					return;
				}

				companySelect.prop('disabled', false).trigger('change');
				hiddenCompany?.remove();
			}

			function setTransferField(id, value) {
				const field = document.getElementById(id);

				if (field) {
					field.value = value || '';
				}
			}

			function setTransferFieldByName(name, value) {
				const field = document.querySelector(`#entity-form [name="${name}"]`);

				if (field) {
					field.value = value || '';
				}
			}

			function setTransferDateField(id, value) {
				const field = document.getElementById(id);

				if (!field) {
					return;
				}

				field.value = value || '';

				if ($.fn.datepicker) {
					$(field).datepicker('update', field.value);
				}
			}

			const modal = document.getElementById("invoiceModal");
			const a4Wrapper = document.getElementById("a4wrapper");
			const previewPages = document.getElementById("previewPages");
			const itemsContainer = document.getElementById("items");
			const createButton = document.getElementById("createInvoice");
			const invoiceTypeInput = document.getElementById("invoice_type");
			const defaultBankDetails = 'Bankverbindung: UniCredit Bank Austria AG, BIC: BKAUATWW, IBAN: AT22 1200 0006 2226 3507';
			let itemIndex = 1;

			document.getElementById("openModal").onclick = () => {
				modal.style.display = "block";
				updatePreview();
				setTimeout(scalePreview, 0);
			};

			document.querySelectorAll('.doc-card').forEach(card => {
				card.addEventListener('click', function() {
					document.querySelectorAll('.doc-card').forEach(item => item.classList.remove('active'));
					this.classList.add('active');
					invoiceTypeInput.value = this.dataset.type || 'rechnung';
					updatePreview();
				});
			});

			document.getElementById("addItem").onclick = function() {
				const row = document.createElement("div");
				row.classList.add("item-row", "col-12");
				row.innerHTML = `
					<div style="position:relative;">
						<input name="items[${itemIndex}][name]" type="text" class="item-name form-control" placeholder="Beschreibung" maxlength="255" autocomplete="off">
						<div class="autocomplete-box beschreibung-box"></div>
					</div>
					<input name="items[${itemIndex}][qty]" type="text" class="item-qty form-control" value="0" autocomplete="off">
					<input name="items[${itemIndex}][price]" type="text" class="item-price form-control" value="0" autocomplete="off">
					<input name="items[${itemIndex}][total]" type="text" class="item-total form-control" value="0" autocomplete="off">
					<button type="button" class="remove-item text-center"><i class="fa fa-times"></i></button>
				`;

				row.querySelector(".remove-item").onclick = () => {
					row.remove();
					updatePreview();
				};

				itemsContainer.appendChild(row);
				itemIndex++;
				updatePreview();
			};

			itemsContainer.addEventListener("input", function(e) {
				if (e.target.matches("input, select")) {
					updatePreview();
				}
			});

			itemsContainer.addEventListener("change", function(e) {
				if (e.target.matches("input, select")) {
					updatePreview();
				}
			});

			document.querySelectorAll([
				"#customer_name",
				"#adress",
				"#ort",
				"#uid",
				"#date",
				"#bvh",
				"#rechnung_nr",
				"#auftragsnr",
				"#ausführungszeit",
				"#discount_percent",
				"#discount_fixed",
				"#deckungsrucklass_percent",
				"#abzug_tr1",
				"#abzug_tr_label",
				"#use_tax",
				"#spacing_input",
				"#invoice_type",
				"#bank_details"
			].join(',')).forEach(input => {
				input.addEventListener("input", updatePreview);
				input.addEventListener("change", updatePreview);
			});

			quill.on('editor-change', updatePreview);

			function updatePreview() {
				const data = getPreviewData();
				const items = getItems(false);
				const previewItems = items.length ? items : [{ name: '', qty: '', price: '', total: 0 }];
				const totals = calculateTotals(items);

				previewPages.innerHTML = '';

				let page = createPage(data, false, true);
				let tbody = page.querySelector('.preview-items');

				previewItems.forEach(item => {
					const row = createItemRow(item);
					tbody.appendChild(row);

					if (pageIsOverflowing(page) && tbody.children.length > 1) {
						row.remove();
						page = createPage(data, true, true);
						tbody = page.querySelector('.preview-items');
						tbody.appendChild(row);
					}
				});

				const summary = createSummary(data, totals);
				page.querySelector('.angebot-page-content').appendChild(summary);

				if (pageIsOverflowing(page) && items.length > 0) {
					summary.remove();
					page = createPage(data, true, false);
					page.querySelector('.angebot-page-content').appendChild(summary);
				}

				updatePageCounters();
				scalePreview();
			}

			function getPreviewData() {
				const type = invoiceTypeInput.value || 'rechnung';

				return {
					type,
					documentLabel: documentLabels[type] || 'Rechnung',
					customerName: valueOf('customer_name'),
					adress: valueOf('adress'),
					ort: valueOf('ort'),
					uid: valueOf('uid'),
					date: formatDisplayDate(valueOf('date')),
					bvh: valueOf('bvh'),
					rechnungNumber: valueOf('rechnung_nr'),
					auftragsnr: valueOf('auftragsnr'),
					ausfuehrungszeit: valueOf('ausführungszeit'),
					note: quill.getText().trim() ? quill.root.innerHTML : '',
					spacing: clamp(parseInt(valueOf('spacing_input'), 10) || 0, 0, 160),
					discountPercent: parseNumber(valueOf('discount_percent')),
					discountFixed: parseNumber(valueOf('discount_fixed')),
					deckungsPercent: parseNumber(valueOf('deckungsrucklass_percent')),
					abzugTr1: parseNumber(valueOf('abzug_tr1')),
					abzugTrLabel: valueOf('abzug_tr_label') || 'Abz. TR 1',
					bankDetails: selectedBankDetails(),
					useTax: document.getElementById('use_tax').checked
				};
			}

			function createPage(data, isContinuation, includeItemsTable = true) {
				const page = document.createElement('div');
				page.className = 'a4-preview';
				page.innerHTML = `
					<div class="angebot-page-content">
						${renderHeader(data)}
						${isContinuation ? '<div class="page-continuation">Fortsetzung</div>' : ''}
						${includeItemsTable ? `
						<table class="invoice-table invoice-table-head">
							<colgroup>
								<col class="col-desc">
								<col class="col-qty">
								<col class="col-price">
								<col class="col-total">
							</colgroup>
							<thead>
								<tr>
									<th>Beschreibung</th>
									<th>Menge</th>
									<th>Einzelpreis</th>
									<th>Betrag</th>
								</tr>
							</thead>
						</table>
						<div class="invoice-table-gap"></div>
						<table class="invoice-table invoice-table-body">
							<colgroup>
								<col class="col-desc">
								<col class="col-qty">
								<col class="col-price">
								<col class="col-total">
							</colgroup>
							<tbody class="preview-items"></tbody>
						</table>` : ''}
					</div>
					<div class="invoice-footer">
						${escapeHtml(data.bankDetails)}
						<span class="page-counter"></span>
					</div>
				`;
				previewPages.appendChild(page);
				return page;
			}

			function renderHeader(data) {
				const uidLine = data.uid ? `UID-Nummer: ${escapeHtml(data.uid)}` : '';
				const bvhLine = data.bvh ? `<p>BVH. ${escapeHtml(data.bvh)}</p>` : '';
				const ausfuehrungszeit = data.ausfuehrungszeit ? `, Ausführungszeit: ${escapeHtml(data.ausfuehrungszeit)}` : '';

				return `
					<div class="header-a4">
						<div class="company-text">
							<p class="company-name">VULETIĆ &amp; ANDRIĆ GmbH</p>
							<div class="company-content">
								<div class="company-details">
									<div class="company-text-left">
										<p>2230 Gänserndorf, Neusiedler Straße 20a</p>
										<p>Tel: 01/985 50 49 - Mob: 0676/480 46 49</p>
										<p>www.va-estriche.at / office@va-estriche.at</p>
										<p>Firmenbuchnummer: 53572 h</p>
										<p>Dienstgebernummer: 700673615</p>
										<p>UID-Nummer: ATU36793706</p>
									</div>
								</div>
								<div class="company-logo">
									<img src="{{ asset('img/full-logo.png') }}" alt="VA Estriche Logo">
								</div>
							</div>
						</div>
					</div>
					<div class="firma" style="margin-top:${data.spacing}px;">
						<p class="customer-lead">${escapeHtml(data.customerName)}</p>
						<p class="customer-address">${escapeHtml(data.adress)}</p>
						<p class="customer-address">${escapeHtml(data.ort)}</p>
					</div>
					<div class="firma-hr"></div>
					<div class="customer-meta">
						<p>${uidLine}</p>
						<p>Datum: ${escapeHtml(data.date)}</p>
					</div>
					<div class="customer">
						${bvhLine}
						<p>${escapeHtml(data.auftragsnr)}</p>
						<p><span class="angebot-title-line">${escapeHtml(data.documentLabel)} ${escapeHtml(data.rechnungNumber)}</span>${ausfuehrungszeit}</p>
					</div>
				`;
			}

			function createItemRow(item) {
				const tr = document.createElement('tr');
				const total = Number.isFinite(item.total) ? formatEuro(item.total) : '';
				tr.innerHTML = `
					<td>${escapeHtml(item.name)}</td>
					<td>${escapeHtml(item.qty)}</td>
					<td>${escapeHtml(item.price)}</td>
					<td><span class="amount-cell"><span>€</span><span>${total}</span></span></td>
				`;
				return tr;
			}

			function createSummary(data, totals) {
				const wrapper = document.createElement('div');
				const afterDiscountPercent = totals.subtotal - totals.discount;
				const afterDiscountFixed = afterDiscountPercent - data.discountFixed;
				const afterDeckungsrucklass = afterDiscountFixed - totals.deckungsrucklass;
				const afterTax = afterDeckungsrucklass + totals.tax;
				const afterAbzugTr1 = afterTax - data.abzugTr1;
				const runningTotalRow = value => `
					<div class="summary-row summary-running-total">
						<span></span>
						<span class="summary-amount"><span>${formatEuro(value)}</span></span>
					</div>`;
				wrapper.className = 'offer-summary-wrap';
				wrapper.innerHTML = `
					<div class="offer-summary">
						<div class="summary-row">
							<span>Zwischensumme</span>
							<span class="summary-amount"><span>${formatEuro(totals.subtotal)}</span></span>
						</div>
						${data.discountPercent > 0 ? `
						<div class="summary-row">
							<span>- ${formatEuro(data.discountPercent, 0)}% Nachlass</span>
							<span class="summary-amount"><span>${formatEuro(totals.discount)}</span></span>
						</div>
						${runningTotalRow(afterDiscountPercent)}` : ''}
						${data.discountFixed > 0 ? `
						<div class="summary-row">
							<span>- Pauschalenachlass</span>
							<span class="summary-amount"><span>${formatEuro(data.discountFixed)}</span></span>
						</div>
						${runningTotalRow(afterDiscountFixed)}` : ''}
						${data.deckungsPercent > 0 ? `
						<div class="summary-row">
							<span>- ${formatEuro(data.deckungsPercent, 0)}% Deckungsrücklass</span>
							<span class="summary-amount"><span>${formatEuro(totals.deckungsrucklass)}</span></span>
						</div>
						${runningTotalRow(afterDeckungsrucklass)}` : ''}
						${data.useTax ? `
						<div class="summary-row">
							<span>+ 20% MwSt</span>
							<span class="summary-amount"><span>${formatEuro(totals.tax)}</span></span>
						</div>` : ''}
						${data.abzugTr1 > 0 ? `
						<div class="summary-row">
							<span>- ${escapeHtml(data.abzugTrLabel)}</span>
							<span class="summary-amount"><span>${formatEuro(data.abzugTr1)}</span></span>
						</div>
						${runningTotalRow(afterAbzugTr1)}` : ''}
						<hr class="summary-divider">
						<div class="summary-row summary-total">
							<span>Gesamtbetrag</span>
							<span class="summary-total-value summary-amount"><span>€</span><span>${formatEuro(totals.total)}</span></span>
						</div>
					</div>
					${data.note ? `<div class="description-left preview-note ql-editor">${data.note}</div>` : ''}
					${!data.useTax ? '<div class="reverse-vat-note"><p>Bauleistung ohne USt. (MwSt. zahlt Empfänger gemäß §19 Abs. 1a UStG 1994)</p></div>' : ''}
				`;
				return wrapper;
			}

			function calculateTotals(items) {
				const data = getPreviewData();
				const subtotal = items.reduce((sum, item) => sum + item.total, 0);
				const discount = data.discountPercent > 0 ? subtotal * (data.discountPercent / 100) : 0;
				let afterDiscount = subtotal - discount;

				if (data.discountFixed > 0) {
					afterDiscount -= data.discountFixed;
				}

				const deckungsrucklass = data.deckungsPercent > 0 ? afterDiscount * (data.deckungsPercent / 100) : 0;
				afterDiscount -= deckungsrucklass;

				const tax = data.useTax ? afterDiscount * 0.20 : 0;
				let total = afterDiscount + tax;

				if (data.abzugTr1 > 0) {
					total -= data.abzugTr1;
				}

				return {
					subtotal: Math.max(subtotal, 0),
					discount: Math.max(discount, 0),
					deckungsrucklass: Math.max(deckungsrucklass, 0),
					tax: Math.max(tax, 0),
					total: Math.max(total, 0)
				};
			}

			function getItems(keepEmpty) {
				return Array.from(document.querySelectorAll("#items .item-row"))
					.map(row => {
						const name = row.querySelector(".item-name")?.value || "";
						const qty = row.querySelector(".item-qty")?.value || "";
						const price = row.querySelector(".item-price")?.value || "";
						const totalRaw = row.querySelector(".item-total")?.value || "";

						return {
							name: name.trim(),
							qty: qty.trim(),
							price: price.trim(),
							total: parseNumber(totalRaw)
						};
					})
					.filter(item => {
						if (keepEmpty) {
							return true;
						}

						return item.name !== ''
							|| (item.qty !== '' && parseNumber(item.qty) !== 0)
							|| parseNumber(item.price) > 0
							|| item.total > 0;
					});
			}

			function pageIsOverflowing(page) {
				if (!page.getClientRects().length) {
					return false;
				}

				const content = page.querySelector('.angebot-page-content');
				const footer = page.querySelector('.invoice-footer');
				const children = Array.from(content.children).filter(child => child.getClientRects().length);
				const contentBottom = children.length
					? Math.max(...children.map(child => child.getBoundingClientRect().bottom))
					: content.getBoundingClientRect().bottom;

				return contentBottom > footer.getBoundingClientRect().top - 10;
			}

			function updatePageCounters() {
				const pages = previewPages.querySelectorAll('.a4-preview');
				pages.forEach((page, index) => {
					const counter = page.querySelector('.page-counter');

					if (pages.length <= 1) {
						counter.textContent = '';
						counter.style.display = 'none';
						return;
					}

					counter.textContent = `${index + 1}/${pages.length}`;
					counter.style.display = 'block';
				});
			}

			function scalePreview() {
				if (!a4Wrapper || !previewPages) {
					return;
				}

				a4Wrapper.style.transform = '';
				a4Wrapper.style.height = '';

				if (window.innerWidth >= 1200) {
					return;
				}

				const pane = document.querySelector('.modal-right');
				const available = Math.max((pane?.clientWidth || window.innerWidth) - 24, 280);
				const scale = Math.min(1, available / 794);
				const rawHeight = previewPages.scrollHeight;

				a4Wrapper.style.transform = `scale(${scale})`;
				a4Wrapper.style.height = `${rawHeight * scale}px`;
			}

			function valueOf(id) {
				return document.getElementById(id)?.value || '';
			}

			function selectedBankDetails() {
				return valueOf('bank_details') || defaultBankDetails;
			}

			function parseNumber(value) {
				let normalized = String(value || '').replace(/[€\s]/g, '').trim();

				if (!normalized) {
					return 0;
				}

				if (normalized.includes(',') && normalized.includes('.')) {
					normalized = normalized.replace(/\./g, '').replace(',', '.');
				} else if (normalized.includes(',')) {
					normalized = normalized.replace(',', '.');
				}

				const parsed = Number.parseFloat(normalized);
				return Number.isFinite(parsed) ? parsed : 0;
			}

			function formatEuro(number, digits = 2) {
				return new Intl.NumberFormat('de-DE', {
					minimumFractionDigits: digits,
					maximumFractionDigits: digits
				}).format(Number(number) || 0);
			}

			function formatDisplayDate(value) {
				if (!value) {
					return '';
				}

				const parts = value.split('-');

				if (parts.length !== 3) {
					return value;
				}

				return `${parts[2]}.${parts[1]}.${parts[0]}`;
			}

			function escapeHtml(value) {
				return String(value || '')
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/"/g, '&quot;')
					.replace(/'/g, '&#039;');
			}

			function clamp(value, min, max) {
				return Math.min(Math.max(value, min), max);
			}

			createButton.onclick = function() {
				const inputs = document.querySelectorAll("#invoiceModal input[required]");

				for (let input of inputs) {
					if (!input.value.trim()) {
						input.classList.add("is-invalid");
						input.focus();
						return;
					}
				}

				updatePreview();

				const data = getPreviewData();
				const items = getItems(false);
				const totals = calculateTotals(items);
				const originalButtonHtml = createButton.innerHTML;

				createButton.disabled = true;
				createButton.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Speichern...';

				$.ajax({
					url: "{{ route('rechnung.store') }}",
					type: "POST",
					data: {
						type: data.type,
						customer_name: $("#customer_name").val(),
						adress: $("#adress").val(),
						ort: $("#ort").val(),
						uid: $("#uid").val(),
						date: $("#date").val(),
						bvh: $("#bvh").val(),
						auftragsnr: $("#auftragsnr").val(),
						rechnung_nr: $("#rechnung_nr").val(),
						ausführungszeit: $("#ausführungszeit").val(),
						invoice_note: quill.root.innerHTML,
						total: formatEuro(totals.total),
						discount_percent: data.discountPercent,
						discount_fixed: data.discountFixed,
						deckungsrucklass_percent: data.deckungsPercent,
						use_tax: data.useTax ? 1 : 0,
						abzug_tr1: data.abzugTr1,
						abzug_tr_label: data.abzugTrLabel,
						spacing_top: data.spacing,
						bank_details: data.bankDetails,
						items: items,
						_token: $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						if (response.pdf_url) {
							window.open(response.pdf_url, "_blank");
						}

						modal.style.display = "none";
						$('#exampledb').DataTable().draw(false);
					},
					error: function(xhr) {
						$('.rechnung-error').text('');
						$('#rechnung_nr').removeClass('is-invalid');

						if (xhr.status === 422) {
							const errors = xhr.responseJSON.errors;

							if (errors?.rechnung_nr?.length) {
								$('#rechnung_nr').addClass('is-invalid');
								$('.rechnung-error').text(errors.rechnung_nr[0]);
							}

							return;
						}

						const message = xhr.responseJSON?.message || 'PDF konnte nicht über DOCX erstellt werden. Bitte prüfen, ob LibreOffice im Docker-Container installiert ist.';
						alert(message);
					},
					complete: function() {
						createButton.disabled = false;
						createButton.innerHTML = originalButtonHtml;
					}
				});
			};

			document.getElementById("closeModal").onclick = () => {
				modal.style.display = "none";
			};

			window.onclick = function(e) {
				if (e.target === modal) {
					modal.style.display = "none";
				}
			};

			const minAutocompleteChars = 2;

			function setupAutocomplete(inputId, boxId, url) {
				const input = document.getElementById(inputId);
				const box = document.getElementById(boxId);
				let timeout;
				let requestId = 0;
				let activeIndex = -1;
				let currentItems = [];

				if (!input || !box) {
					return;
				}

				function close() {
					box.innerHTML = '';
					currentItems = [];
					activeIndex = -1;
				}

				function render(items) {
					currentItems = items;
					activeIndex = items.length ? 0 : -1;
					box.innerHTML = '';

					items.forEach((item, index) => {
						const div = document.createElement('div');
						div.className = 'autocomplete-item' + (index === activeIndex ? ' is-active' : '');
						div.textContent = item;
						div.addEventListener('mousedown', event => {
							event.preventDefault();
							input.value = item;
							close();
							updatePreview();
						});
						box.appendChild(div);
					});
				}

				input.addEventListener('input', function() {
					const query = input.value.trim();
					clearTimeout(timeout);

					if (query.length < minAutocompleteChars) {
						close();
						return;
					}

					const currentRequest = ++requestId;
					timeout = setTimeout(() => {
						fetch(`${url}?q=${encodeURIComponent(query)}`)
							.then(response => response.json())
							.then(items => {
								if (currentRequest !== requestId) {
									return;
								}

								render(Array.isArray(items) ? items : []);
							})
							.catch(close);
					}, 180);
				});

				input.addEventListener('keydown', function(event) {
					if (!currentItems.length) {
						return;
					}

					if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
						event.preventDefault();
						activeIndex = event.key === 'ArrowDown'
							? (activeIndex + 1) % currentItems.length
							: (activeIndex - 1 + currentItems.length) % currentItems.length;
						render(currentItems);
					}

					if (event.key === 'Enter' && activeIndex >= 0) {
						event.preventDefault();
						input.value = currentItems[activeIndex];
						close();
						updatePreview();
					}

					if (event.key === 'Escape') {
						close();
					}
				});

				document.addEventListener('mousedown', function(event) {
					if (!box.contains(event.target) && event.target !== input) {
						close();
					}
				});
			}

			function setupBeschreibungAutocomplete() {
				const wiredInputs = new WeakSet();

				function wireInput(input) {
					if (wiredInputs.has(input)) {
						return;
					}

					wiredInputs.add(input);

					const box = input.parentElement.querySelector(".beschreibung-box");
					let timeout;
					let requestId = 0;
					let activeIndex = -1;
					let currentItems = [];

					function choose(index) {
						if (!currentItems[index]) {
							return;
						}

						input.value = currentItems[index];
						box.innerHTML = "";
						activeIndex = -1;
						currentItems = [];
						updatePreview();
					}

					function render(items) {
						currentItems = items;
						activeIndex = items.length ? 0 : -1;
						box.innerHTML = "";

						items.forEach((item, index) => {
							const div = document.createElement("div");
							div.classList.add("autocomplete-item");

							if (index === activeIndex) {
								div.classList.add("is-active");
							}

							div.innerText = item;
							div.addEventListener("mousedown", e => e.preventDefault());
							div.addEventListener("click", () => choose(index));
							box.appendChild(div);
						});
					}

					function updateActive() {
						box.querySelectorAll(".autocomplete-item").forEach((item, index) => {
							item.classList.toggle("is-active", index === activeIndex);

							if (index === activeIndex) {
								item.scrollIntoView({ block: "nearest" });
							}
						});
					}

					function requestSuggestions() {
						const query = input.value.trim();
						clearTimeout(timeout);
						requestId++;

						if (query.length < minAutocompleteChars) {
							box.innerHTML = "";
							currentItems = [];
							activeIndex = -1;
							return;
						}

						timeout = setTimeout(() => {
							const currentRequestId = requestId;

							fetch(`/rechnung/autocomplete/beschreibung?q=${encodeURIComponent(query)}`)
								.then(response => response.json())
								.then(data => {
									if (currentRequestId === requestId && input.value.trim() === query) {
										render(Array.isArray(data) ? data : []);
									}
								});
						}, 300);
					}

					input.addEventListener("keydown", function(e) {
						if (!currentItems.length) {
							return;
						}

						if (e.key === "ArrowDown") {
							e.preventDefault();
							activeIndex = (activeIndex + 1) % currentItems.length;
							updateActive();
						}

						if (e.key === "ArrowUp") {
							e.preventDefault();
							activeIndex = activeIndex <= 0 ? currentItems.length - 1 : activeIndex - 1;
							updateActive();
						}

						if (e.key === "Enter") {
							e.preventDefault();
							choose(activeIndex);
						}

						if (e.key === "Escape") {
							box.innerHTML = "";
							currentItems = [];
							activeIndex = -1;
						}
					});

					input.addEventListener("input", requestSuggestions);
					requestSuggestions();
				}

				document.addEventListener("input", function(e) {
					if (!e.target.classList.contains("item-name")) {
						return;
					}

					wireInput(e.target);
				});

				document.querySelectorAll(".item-name").forEach(wireInput);

				document.addEventListener("mousedown", function(e) {
					document.querySelectorAll(".beschreibung-box").forEach(box => {
						const input = box.parentElement.querySelector(".item-name");

						if (!box.contains(e.target) && e.target !== input) {
							box.innerHTML = "";
						}
					});
				});
			}

			setupAutocomplete("customer_name", "firma_box", "/rechnung/autocomplete/firma");
			setupAutocomplete("adress", "adress_box", "/rechnung/autocomplete/adress");
			setupBeschreibungAutocomplete();

			function setValue(id, value) {
				const element = document.getElementById(id);

				if (!element) {
					return;
				}

				element.value = value || '';
				element.dispatchEvent(new Event('input', { bubbles: true }));
				element.dispatchEvent(new Event('change', { bubbles: true }));
			}

			$('#firma_select').on('change', function() {
				const selected = $(this).find(':selected')[0];

				if (!this.value || !selected) {
					return;
				}

				setValue('customer_name', selected.dataset.name);
				setValue('adress', selected.dataset.adress);
				setValue('ort', selected.dataset.ort);
				setValue('uid', selected.dataset.uid);
				updatePreview();
			});

			if ($.fn.select2) {
				$('#firma_select').select2({
					placeholder: 'Firma suchen...',
					allowClear: true,
					width: '100%'
				});
			}

			window.addEventListener('resize', scalePreview);
			updatePreview();

			document.querySelectorAll("input").forEach(input => {
				input.addEventListener("input", () => input.classList.remove("is-invalid"));
			});

			$('#rechnung_nr').on('input', function() {
				$('.rechnung-error').text('');
				$(this).removeClass('is-invalid');
			});

			const params = new URLSearchParams(window.location.search);
			if (params.get('openModal') === '1') {
				modal.style.display = "block";
				updatePreview();
				window.history.replaceState({}, document.title, window.location.pathname);
			}
		});
	</script>
@endpush
