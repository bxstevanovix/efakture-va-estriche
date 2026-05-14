<div class="btn-group" role="group" aria-label="Basic example">
    <a 
        href="{{ route('angebote.view', $entity->id) }}" 
        class="btn light btn-info printInvoiceButton"
        data-url="{{ asset('storage/' . $entity->invoice_url) }}" 
        target="_blank"
        title="@lang('PDF')"
        >
    <i class="fas fa-file-pdf"></i>
    </a>
    <button style="width: 100%;" class="btn btn-danger swal-confirm"
        data-title="{{ __('Da li ste sigurni da želite da obrišete predračun :id?', ['id' => $entity->id_invoice]) }}"
        data-desc="@lang('Ova stavka se neće moći vratiti!')"
        data-id="{{ $entity->id }}"
        data-url="{{route('angebote.delete', ['entity' => $entity->id])}}"
        data-success="@lang('Predracun je uspešno obrisan!')"
        data-table="#exampledb"
        data-confirm-text="@lang('Obrisi')"
        data-cancel-text="@lang('Odustani')">
        <i class="fa fa-trash"></i>
    </button>
</div>

<script>
	initSweetAlert('.swal-confirm');
</script>