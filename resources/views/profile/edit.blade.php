@extends('_layouts.layout')

@section('head_title', __('Dashboard'))

@push('head_links')
<style>
    .card {
        border-radius: 12px;
    }

    .card-header {
        border-bottom: 1px solid #eee;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="row">

        <!-- PROFILE INFO -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profil</h4>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Promjena šifre')</h4>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- DELETE -->
        <div class="col-xl-12">
            <div class="card border-danger">
                <div class="card-header">
                    <h4 class="card-title text-danger">@lang('Brisanje Naloga')</h4>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('footer_scripts')

</script>

@endpush