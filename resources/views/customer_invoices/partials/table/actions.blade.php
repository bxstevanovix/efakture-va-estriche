
<div class="btn-group">
    @if ($entity->pdf)
        <a 
            href="{{ route('customer-invoices.view', $entity->id) }}" 
            target="_blank" 
            class="btn btn-info" 
            title="Prikaži PDF"
        >
            <i class="fa fa-eye"></i> PDF
        </a>
    @else
        <button 
            type="button" 
            class="btn btn-warning upload-pdf-btn" 
            title="Dodaj PDF"
            data-id="{{ $entity->id }}"
        >
            <i class="fa fa-upload"></i> PDF
        </button>
    @endif
    <a 
        href="{{ route('customer-invoices.edit', ['entity' => $entity->id]) }}" 
        class="btn btn-primary"
        title="@lang('Izmeni')"
    >
        <i class="fa fa-pencil"></i>
    </a>
    <button style="width: 100%;" class="btn btn-danger swal-confirm"
        data-title="{{ __('Da li ste sigurni da želite da obrišete račun :id?', ['id' => $entity->id_invoice]) }}"
        data-desc="@lang('Ova stavka se neće moći vratiti!')"
        data-id="{{ $entity->id }}"
        data-url="{{route('customer-invoices.delete', ['entity' => $entity->id])}}"
        data-success="@lang('Racun je uspešno obrisan!')"
        data-table="#exampledb"
        data-confirm-text="@lang('Obrisi')"
        data-cancel-text="@lang('Odustani')">
        <i class="fa fa-trash"></i>
    </button>
</div>

<form id="uploadPdfForm" action="{{ route('customer-invoices.upload_pdf') }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="entity_id" id="pdfEntityId">
    <input type="hidden" name="entity_name" value="{{$entity->name}}">
    <input type="file" name="pdf_file" id="pdfFileInput" accept="application/pdf">
</form>
    
<script>
    initSweetAlert('.swal-confirm');

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.upload-pdf-btn');
        if (!button) return;

        const entityId = button.dataset.id;
        const input = document.getElementById('pdfFileInput');

        document.getElementById('pdfEntityId').value = entityId;

        input.onchange = function () {
            if (this.files.length > 0) {

                let form = document.getElementById('uploadPdfForm');
                let formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        toastr.success(response.message); // ✅ toast
                        $('#exampledb').DataTable().ajax.reload(null, false); 
                    } else {
                        toastr.error('Greška!');
                    }
                })
                .catch(() => {
                    toastr.error('Upload nije uspeo!');
                });

            }
        };

        input.click();
    });
</script>

