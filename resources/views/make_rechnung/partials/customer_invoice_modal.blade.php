<style>
    #sendToCustomerInvoiceModal input.transfer-readonly[readonly] {
        background-color: #fff !important;
        color: #2f3a4a !important;
        opacity: 1;
        cursor: default;
    }

    #sendToCustomerInvoiceModal .select2-container--disabled .select2-selection {
        background-color: #fff !important;
        color: #2f3a4a !important;
        opacity: 1;
        cursor: default;
    }

    #sendToCustomerInvoiceModal .invoice-inline-row {
        margin-top: 16px !important;
    }

    #sendToCustomerInvoiceModal .invoice-inline-field {
        display: block;
    }

    #sendToCustomerInvoiceModal .invoice-inline-field label {
        display: block;
        margin-bottom: 6px !important;
        white-space: normal !important;
    }

    #sendToCustomerInvoiceModal .invoice-inline-field .input-group {
        width: 100%;
        min-width: 180px;
    }

    #sendToCustomerInvoiceModal input[name="price"] {
        min-width: 0;
        font-variant-numeric: tabular-nums;
    }
</style>

<div class="modal fade" id="sendToCustomerInvoiceModal" tabindex="-1" aria-labelledby="sendToCustomerInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendToCustomerInvoiceModalLabel">@lang('Pošalji u Izlazne račune')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Zatvori')"></button>
            </div>
            <div class="modal-body">
                <div id="transferCompanyWarning" class="alert alert-warning d-none" role="alert">
                    @lang('Firma nije automatski pronađena. Izaberite firmu prije snimanja.')
                </div>
                <div id="transferPdfWarning" class="alert alert-warning d-none" role="alert">
                    @lang('PDF za ovaj račun nije pronađen. Faktura će biti kreirana bez PDF dokumenta.')
                </div>
                @include('customer_invoices.partials.form', [
                    'entity' => $customerInvoiceEntity,
                    'companies' => $companies,
                    'formAction' => route('customer-invoices.create'),
                    'includeSourceRechnungField' => true,
                    'submitLabel' => __('Pošalji u Izlazne račune'),
                ])
            </div>
        </div>
    </div>
</div>
