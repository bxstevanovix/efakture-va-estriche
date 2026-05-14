@php
    use App\Models\InvoicePdf;
    $pdfs = InvoicePdf::where('invoice_id', $entity->id)->where('invoice_type', 'customer')->get();
@endphp

<style>
    .invoice-documents .upload-pdf-btn {
        min-height: 40px;
    }

    .invoice-documents .quick-bx-inner {
        border: 1px solid #eef1f7;
        border-radius: 8px;
        padding: 12px;
        min-height: 58px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .invoice-documents .quick-bx-inner h6 {
        overflow-wrap: anywhere;
        line-height: 1.35;
    }

    @media (max-width: 767px) {
        .invoice-documents {
            margin-top: 28px;
        }

        .invoice-documents .upload-pdf-btn {
            width: 100%;
        }

        .invoice-documents .quick-bx-inner {
            min-height: 0;
        }
    }
</style>

<hr style="margin-top:60px;">
<div class="quick-bx invoice-documents">
    <h4 class="card-title mb-3">@lang('Dokumenti')</h4>
    <div class="row">
        <div class="mb-3">
            <button 
                type="button" 
                class="btn btn-warning upload-pdf-btn" 
                title="Dodaj PDF"
                data-id="{{ $entity->id }}"
            >
                <i class="fa fa-upload"></i> Upload PDF
            </button>
            <form id="uploadPdfForm" action="{{ route('customer-invoices.upload_more_pdfs') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="hidden" name="entity_id" id="pdfEntityId">
                <input type="hidden" name="entity_name" value="{{$entity->name}}">
                <input type="file" name="pdf_file" id="pdfFileInput" accept="application/pdf">
            </form>
            
        </div>
        @forelse($pdfs as $pdf)
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="quick-bx-inner mb-3">
                    <div class="d-flex align-items-center">
                        <i class="flaticon-document fs-18 text-primary"></i>

                        <a href="{{ asset('storage/' . $pdf->file_path) }}" target="_blank">
                            <h6 class="mb-0 ms-2 fs-15">
                                {{ $pdf->file_name }}
                            </h6>
                        </a>
                    </div>

                    <div class="dropdown c-pointer">
                        <div class="btn-link" data-bs-toggle="dropdown">
                            <svg width="16" height="16" viewBox="0 0 24 24">
                                <circle cx="12.5" cy="3.5" r="2.5" fill="#A5A5A5"/>
                                <circle cx="12.5" cy="11.5" r="2.5" fill="#A5A5A5"/>
                                <circle cx="12.5" cy="19.5" r="2.5" fill="#A5A5A5"/>
                            </svg>
                        </div>

                        <div class="dropdown-menu dropdown-menu-right">
                            <form action="{{ route('invoice-pdfs.delete', $pdf->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"
                                    onclick="return confirm('Da li ste sigurni?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <p>@lang('Nema dokumenata')</p>
            </div>
        @endforelse

    </div>
</div>
<hr>

<script>
    document.addEventListener('click', function (e) {
        const button = e.target.closest('.upload-pdf-btn');
        if (!button) return;

        const entityId = button.dataset.id;
        const input = document.getElementById('pdfFileInput');

        document.getElementById('pdfEntityId').value = entityId;

        input.onchange = function () {
            if (this.files.length > 0) {
                document.getElementById('uploadPdfForm').submit();
            }
        };

        input.click();
    });
</script>
