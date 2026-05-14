<div class="btn-group">
    <a 
        href="{{route('firme.edit', ['entity' => $entity->id])}}" 
        class="btn btn-primary"
        title="@lang('Izmeni')"
    >
        <i class="fa fa-pencil"></i>
    </a>
    <button 
        class="btn btn-danger swal-confirm"
        data-title="@lang('Da li ste sigurni da zelite da obrisete firmu?')"
        data-desc="@lang('Ova stavka se neće moći vratiti!')"
        data-id="{{ $entity->id }}"
        data-url="{{route('firme.delete', ['entity' => $entity->id])}}"
        data-success="@lang('Firma je uspešno obrisana1!')"
        data-table="#exampledb"
        data-confirm-text="@lang('Obrisi')"
        data-cancel-text="@lang('Odustani')">
        <i class="fa fa-trash"></i>
    </button>
</div>

<script>
	initSweetAlert('.swal-confirm');
</script>