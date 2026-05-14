@extends('_layouts.layout')

@section('head_title', __('Kreiranje') . ' - ' . __('Ulazne Fakture'))

@push('head_links')

@endpush

@section('content')

<style>
    .supplier-invoice-form-page .card-header {
        gap: 12px;
    }

    @media (max-width: 767px) {
        .supplier-invoice-form-page .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .supplier-invoice-form-page .card-header .btn {
            width: auto;
            margin-top: 0;
            white-space: nowrap;
        }

        .supplier-invoice-form-page .card-title {
            min-width: 0;
            margin-right: 12px;
            overflow-wrap: anywhere;
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
		<li class="breadcrumb-item active">@lang('Kreiranje Fakture')</li>
	</ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">{{$entity->id_invoice ? $entity->id_invoice : __('Nova faktura')}}</h4>
                
                <a href="{{route('supplier-invoices.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
            </div>
            <div class="card-body">
                @include('supplier_invoices.partials.form', ['entity' => $entity])
            </div>
        </div>
    </div>
</div>
</div>

@endsection
