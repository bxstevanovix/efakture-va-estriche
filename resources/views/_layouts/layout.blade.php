<!DOCTYPE html>
<html lang="de">
    <head>
        <title>E-faktura | VA Estriche</title>
        <script>
            (function () {
                var key = 'va-estriche-show-preloader';
                var showPreloader = false;

                try {
                    showPreloader = window.sessionStorage.getItem(key) === '1';
                    window.sessionStorage.removeItem(key);
                } catch (error) {
                    showPreloader = false;
                }

                document.documentElement.classList.add(showPreloader ? 'preloader-enabled' : 'preloader-suppressed');
            })();
        </script>
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
            (function () {
                var key = 'va-estriche-show-preloader';
                var sectionRoutes = [
                    '/dashboard',
                    '/firme',
                    '/customer-invoices',
                    '/supplier-invoices',
                    '/supplier-invoice',
                    '/rechnung',
                    '/angebote'
                ];

                function isSectionRoute(pathname) {
                    return sectionRoutes.some(function (route) {
                        return pathname === route || pathname.indexOf(route + '/') === 0;
                    });
                }

                document.addEventListener('click', function (event) {
                    var link = event.target.closest('a[href]');

                    if (! link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    if (link.target && link.target !== '_self') {
                        return;
                    }

                    var url;

                    try {
                        url = new URL(link.href, window.location.href);
                    } catch (error) {
                        return;
                    }

                    if (url.origin !== window.location.origin || url.href === window.location.href || ! isSectionRoute(url.pathname)) {
                        return;
                    }

                    try {
                        window.sessionStorage.setItem(key, '1');
                    } catch (error) {
                    }
                });
            })();

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(function () {});
                });
            }
        </script>
    </body>
</html>
