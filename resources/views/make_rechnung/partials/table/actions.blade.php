<div class="d-flex justify-content-end">
    <a
        href="{{ route('rechnung.view', $entity->id) }}"
        class="btn btn-info shadow btn-s sharp me-1 printInvoiceButton"
        data-url="{{ asset('storage/' . $entity->invoice_url) }}"
        target="_blank"
        title="@lang('PDF')"
    >
        <i class="fas fa-file-pdf"></i>
    </a>
    <button
        type="button"
        class="btn btn-danger shadow btn-s sharp me-1 swal-confirm"
        data-title="{{ __('Da li ste sigurni da želite da obrišete račun :id?', ['id' => $entity->id_invoice]) }}"
        data-desc="@lang('Ova stavka se neće moći vratiti!')"
        data-id="{{ $entity->id }}"
        data-url="{{ route('rechnung.delete', ['entity' => $entity->id]) }}"
        data-success="@lang('Racun je uspešno obrisan!')"
        data-table="#exampledb"
        data-confirm-text="@lang('Obrisi')"
        data-cancel-text="@lang('Odustani')"
    >
        <i class="fa fa-trash"></i>
    </button>
    @if($hasCustomerInvoice ?? false)
        <button
            type="button"
            class="btn btn-primary shadow btn-s sharp"
            title="@lang('Već je poslato u Ausgangsrechnungen')"
            disabled
        >
            <i class="fa fa-check"></i>
        </button>
    @else
        <button
            type="button"
            class="btn btn-primary shadow btn-s sharp send-to-customer-invoice"
            data-url="{{ route('rechnung.customer_invoice_data', $entity->id) }}"
            title="@lang('Pošalji u Izlazne račune')"
        >
            <i class="fa fa-plus"></i>
        </button>
    @endif
</div>

<script>
    initSweetAlert('.swal-confirm');
</script>
