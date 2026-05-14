@extends('_layouts.layout')

@section('head_title', __('Kreiranje Firme'))

@push('head_links')

@endpush

@section('content')
<style>
    .error{
        color: red;
    }

    .company-form-page .card-header {
        gap: 12px;
    }

    .company-form-page .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 8px;
        border-top: 1px solid #eef1f7;
    }

    @media (max-width: 767px) {
        .company-form-page .card-header {
            display: block;
        }

        .company-form-page .card-header .btn {
            width: 100%;
            margin-top: 12px;
        }

        .company-form-page .card-body {
            padding: 18px;
        }

        .company-form-page .form-actions {
            display: block;
        }

        .company-form-page .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Kreiranje Firme')</li>
	</ol>
</div>

<div class="company-form-page">
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">@lang('Kreiranje Nove Firme')</h4>
                
                <a href="{{route('firme.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
			</div>
            <div class="card-body">
                <div class="form-validation">
                    @include('firme.partials.form', ['entity' => $entity])
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
