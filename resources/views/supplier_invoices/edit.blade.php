@extends('_layouts.layout')

@section('head_title', __('Editovanje') . ' - ' . __('Ulazne fakture'))

@push('head_links')

@endpush

@section('content')

<style>
    .supplier-invoice-form-page .card-header {
        gap: 12px;
    }

    .supplier-invoice-form-page .invoice-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    @media (max-width: 767px) {
        .supplier-invoice-form-page .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .supplier-invoice-form-page .invoice-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: nowrap;
            gap: 8px;
            margin-top: 0;
            margin-left: 12px;
        }

        .supplier-invoice-form-page .invoice-header-actions .btn,
        .supplier-invoice-form-page .invoice-header-actions form,
        .supplier-invoice-form-page .invoice-header-actions form .btn {
            width: auto;
            white-space: nowrap;
        }

        .supplier-invoice-form-page .card-title {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .supplier-invoice-form-page .invoice-header-actions .btn-danger {
            max-width: 132px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .supplier-invoice-form-page .card-body {
            padding: 18px;
        }
    }
</style>

<div class="supplier-invoice-form-page">
<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('supplier-invoices.index')}}">@lang('Ulazne fakture')</a></li>
		<li class="breadcrumb-item active">@lang('Editovanje Fakture')</li>
	</ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">{{$entity->id_invoice}}</h4>
                <div class="invoice-header-actions">
                    @if ($entity->pdf)
                        <form action="{{ route('supplier-invoices.delete_pdf', $entity) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Sind Sie sicher, dass Sie den PDF löschen möchten?')" class="btn btn-danger btn-sm">
                                Haupt-PDF löschen
                            </button>
                        </form>
                    @endif
                    <a href="{{route('supplier-invoices.index')}}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        @lang('Nazad')
                    </a>
                </div>
			</div>
            <div class="card-body">
                @include('supplier_invoices.partials.form', ['entity' => $entity])
            </div>
        </div>
    </div>
</div>
</div>

@endsection
