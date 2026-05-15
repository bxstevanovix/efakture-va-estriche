<!DOCTYPE html>
<html lang="de">
    <head>
        <title>E-faktura | VA Estriche</title>
        @include('_layouts.partials.head')
    </head>
    <body>
        <div id="preloader">
            <div class="lds-ripple">
                <div class="item1"></div>
                <div class="item2"></div>
                <div class="item3"></div>
                <div class="item4"></div>
            </div>
            <h3 class="dz-loader-h">E-faktura</h3>
        </div>

        <div id="main-wrapper">

            @include('_layouts.partials.header')
            @include('_layouts.partials.sidebar')

            <div class="content-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <div class="footer">
                <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="https://www.fakture.at/" target="_blank">e-faktura</a> <span class="current-year">2026</span></p>
                </div>
            </div>
        </div>

        @stack('modals')

        
        @include('_layouts.partials.scripts')

        @include('_layouts.partials.toastr')

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(function () {});
                });
            }
        </script>
    </body>
</html>
