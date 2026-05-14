@extends('_layouts.layout')

@section('head_title', __('Kreiranje') . ' - ' . __('Izlane Fakture'))

@push('head_links')

@endpush

@section('content')

<style>
    .customer-invoice-form-page .card-header {
        gap: 12px;
    }

    @media (max-width: 767px) {
        .customer-invoice-form-page .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .customer-invoice-form-page .card-header .btn {
            width: auto;
            margin-top: 0;
            white-space: nowrap;
        }

        .customer-invoice-form-page .card-title {
            min-width: 0;
            margin-right: 12px;
            overflow-wrap: anywhere;
        }

        .customer-invoice-form-page .card-body {
            padding: 18px;
        }
    }
</style>

<div class="customer-invoice-form-page">
<div class="row page-titles mx-0">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('customer-invoices.index')}}">@lang('Izlazne fakture')</a></li>
		<li class="breadcrumb-item active">@lang('Kreiranje Fakture')</li>
	</ol>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">{{$entity->id_invoice ? $entity->id_invoice : __('Nova faktura')}}</h4>
                
                <a href="{{route('customer-invoices.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
            </div>
            <div class="card-body">
                @include('customer_invoices.partials.form', ['entity' => $entity])
            </div>
        </div>
    </div>
</div>
</div>

@endsection
