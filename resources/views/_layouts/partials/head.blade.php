<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $timestamp = date('d-H');
@endphp

@stack('head_links')





<link rel="stylesheet" href="{{ asset('files/vendor/select2/css/select2.min.css') }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v=14">
<link rel="icon" type="image/svg+xml" href="{{ asset('f-circle.svg') }}">
<link rel="shortcut icon" type="image/svg+xml" href="{{ asset('f-circle.svg') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('f-circle.svg') }}">
<link rel="apple-touch-icon-precomposed" sizes="180x180" href="{{ asset('f-circle.svg') }}">
<meta name="application-name" content="E-faktura">
<meta name="apple-mobile-web-app-title" content="E-faktura">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#5746A3">
<link href="{{ asset('files/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{ asset('files/vendor/datatables/css/buttons.dataTables.min.css') }}" rel="stylesheet">	
<link href="{{asset('files/vendor/datatables/responsive/responsive.css')}}" rel="stylesheet">	
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<link href="{{ asset('files/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('files/vendor/nouislider/nouislider.min.css') }}">
<link href="{{ asset('files/css/style.css') }}?v={{ filemtime(public_path('files/css/style.css')) }}" rel="stylesheet">
<link href="{{ asset('files/css/custom.css') }}?v={{ filemtime(public_path('files/css/custom.css')) }}" rel="stylesheet">

<!-- Toastr i Sweetalert -->
<link rel="stylesheet" href="{{ asset('files/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('files/vendor/sweetalert2/dist/sweetalert2.min.css') }}">

<link href="{{ asset('files/vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.38.2/skin-vista/ui.fancytree.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



@stack('head_scripts')
