<div class="nav-header">
    <a href="{{ url('/') }}" class="brand-logo d-flex align-items-center">

        <img src="{{ asset('f-circle.svg') }}" alt="E-faktura" style="width:42px; height:42px; border-radius:10px;">

        <div>
            <div class="brand-title">
                <span style="font-size: 24px; font-weight: bold; color: var(--primary);">E-faktura</span>
            </div>
        </div>

    </a>
    <div class="nav-control">
        <div class="hamburger">
            <span class="line">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.648 12.295C8.02853 12.6755 8.02853 13.2927 7.648 13.6732C7.26748 14.054 6.65031 14.054 6.26963 13.6732L0.285192 7.68899C0.0950229 7.49873 0 7.2493 0 6.9999C0 6.75056 0.0952077 6.50116 0.285192 6.31099L6.26963 0.326737C6.65031 -0.0539397 7.26748 -0.0539397 7.648 0.326737C8.02853 0.70726 8.02853 1.32434 7.648 1.70505L2.35296 6.9999L7.648 12.295ZM8.41958 6.9999L13.7147 1.70502C14.0952 1.32434 14.0952 0.707229 13.7147 0.326706C13.3341 -0.0539705 12.7169 -0.0539705 12.3363 0.326706L6.35184 6.31099C6.16167 6.50135 6.06662 6.75056 6.06662 6.9999C6.06662 7.24933 6.16186 7.49873 6.35184 7.68899L12.3363 13.6732C12.7169 14.054 13.3341 14.054 13.7147 13.6732C14.0952 13.2926 14.0952 12.6755 13.7147 12.295L8.41958 6.9999Z" fill="var(--primary)"/>
                    </svg>
                    
            </span>
        </div>
    </div>
</div>

<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div style="color: var(--primary);" class="dashboard_bar">
                       VA ESTRICHE
                    </div>
                   
                </div>
                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link bell dz-theme-mode p-0" href="javascript:void(0);">
                            <i id="icon-light" class="flaticon-sun"></i>
                            <i id="icon-dark" class="flaticon-night-mode"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown header-profile ps30">
                        <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('img/user-no-image.png') }}" width="20" alt="">
                            <div class="header-info ms-2 me-3">
                                <span class="fs-13 font-w500 mb-0">{{ auth()->user()->name }}</span>
                                {{-- <small class="fs-12">Super Admin</small> --}}
                            </div>
                            <div class="ms-auto me-1">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.2803 2.8447L5.99998 7.56435L10.7197 2.8447C10.8606 2.70545 11.0509 2.62764 11.249 2.62824C11.4472 2.62884 11.637 2.70781 11.7771 2.8479C11.9172 2.988 11.9962 3.17784 11.9968 3.37596C11.9974 3.57408 11.9195 3.76439 11.7803 3.90534L6.5303 9.15534C6.38965 9.29599 6.19889 9.375 5.99998 9.375C5.80108 9.375 5.61031 9.29599 5.46966 9.15534L0.219662 3.90534C0.0804205 3.76439 0.00260448 3.57408 0.0032053 3.37596C0.00380707 3.17784 0.082778 2.988 0.222872 2.8479C0.362967 2.70781 0.552803 2.62884 0.750925 2.62824C0.949048 2.62764 1.13936 2.70545 1.2803 2.8447Z" fill="#ADADAD"/>
                                    </svg>
                                    
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="card shadow-none border-0 mb-0">
                                
                                <div class="card-body px-0 py-2">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item ai-icon ">
                                        <i class="flaticon-puzzle fs-18"></i>

                                        <span class="ms-2">@lang('Profil') </span>
                                    </a>
                                </div>
                                <div class="card-footer px-0 py-2">
                                    
                                    <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item ai-icon">
                                        <i class="flaticon-logout-1 fs-18 text-danger"></i>
                                        <span class="ms-2 text-danger">@lang('Logout')</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
