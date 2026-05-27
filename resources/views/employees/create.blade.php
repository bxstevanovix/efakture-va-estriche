@extends('_layouts.layout')

@section('head_title', __('Kreiraj radnika'))

@section('content')
<style>
    .employee-form-page .card-header {
        gap: 12px;
    }

    @media (max-width: 767px) {
        .employee-form-page .card-header {
            display: block;
        }

        .employee-form-page .card-header .btn {
            width: 100%;
            margin-top: 12px;
        }

        .employee-form-page .card-body {
            padding: 18px;
        }
    }
</style>

<div class="employee-form-page">
    <div class="row page-titles mx-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">@lang('Radnici')</a></li>
            <li class="breadcrumb-item active">@lang('Kreiranje')</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card h-auto">
                <div class="card-header">
                    <h4 class="card-title">@lang('Kreiraj radnika')</h4>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        @lang('Nazad')
                    </a>
                </div>
                <div class="card-body">
                    @include('employees.partials.form', [
                        'entity' => $entity,
                        'statuses' => $statuses,
                        'contractTypes' => $contractTypes,
                        'documentTypes' => $documentTypes,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
