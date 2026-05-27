<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="menu-title border-top-0 pt-2">@lang('Main Menu')</li>


            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="flaticon-layout text-primary"></i>
                    <span class="nav-text">@lang('Dashboard')</span>
                </a>
            </li>

            {{-- <li><a href="{{ route("projekti.index") }}" class="" aria-expanded="false">
                    <i class="flaticon-app text-primary"></i>
                    <span class="nav-text">Projekti</span>
                </a>
            </li> --}}

            <li>
                <a href="{{ route('employees.index') }}" class="" aria-expanded="false">
                <svg data-chakra-component="CIcon" viewBox="13.03 9.88 69.72 69.79" role="presentation" class="css-5ffdf css-3cz70w css-0">
                    <g><path fill="currentColor" d="M68.77 71.57c-1 0-2.2-.07-3.09-.13-.52 0-.91-.06-1.07 0l-.1-2h1.3c.76 0 2 .13 3 .13 8.54 0 11.41-1.75 11.81-2.85v-.52c-.12-6.42-.92-7.35-1.08-7.47-1-.74-4.47-2.79-6.19-3.79a7.54 7.54 0 01-2.88 1.45 5 5 0 01-1.58.31A8.56 8.56 0 0164 55.63l-.75-.48-.25-.22-.77.4c-.54.27-1.14.58-1.4.73l-1-1.73c.27-.16.92-.49 1.5-.79l1-.49.23-.14h.11a1.5 1.5 0 011.38.25c.17.15.3.25.43.35l.75.48a7 7 0 003.38.74h.19a3.22 3.22 0 001.06-.21h.12a5.54 5.54 0 002.36-1.26 1.58 1.58 0 011.43-.29h.07l.27.14c2 1.17 5.57 3.26 6.67 4.08s1.77 3.54 1.88 9v.43l.09.21-.14.37c-.47 1.57-2.8 4.37-13.84 4.37zM73 54.78z" data-name="Path 2"></path>
                    <path fill="currentColor" d="M39.21 79.67c-21.94 0-25.49-6-26-8.57a1.09 1.09 0 010-.26C13 69 13 67.47 13.06 66.49c.06-4.19.68-9.55 3.27-11.3 1.7-1.11 6.19-3.59 10.8-6.1.72-.36 1.34-.71 1.86-1l.42-.22a2.13 2.13 0 012.25.19 12.76 12.76 0 005.11 2.49 12.25 12.25 0 002.62.47 8.29 8.29 0 002.38-.44h.1a12.82 12.82 0 005.13-2.5 2 2 0 012.29-.2.83.83 0 01.15.11l2.16 1.17c1.75.94 3.86 2.08 5.78 3.15 2.21 1.23 4.07 2.32 5 2.94 2.4 1.64 3 7.73 3.16 12.54a18.57 18.57 0 01-.17 4.45 5.42 5.42 0 01-.94 1.76c-2.09 2.57-8.15 5.67-25.22 5.67zm-24-9c.23 1.16 2.47 7 24 7 17.77 0 22.46-3.46 23.65-5a3.13 3.13 0 00.54-1.06c.3-1.85 0-13.18-2.22-14.73-.83-.57-2.67-1.66-4.8-2.84-1.92-1.07-4-2.2-5.76-3.14l-2.29-1.24-.09-.06a14.69 14.69 0 01-5.87 2.86 10 10 0 01-3 .54 13.76 13.76 0 01-3.09-.54 14.85 14.85 0 01-5.84-2.84.09.09 0 00-.09 0l-.37.21c-.55.3-1.19.65-1.9 1-2.83 1.54-8.77 4.78-10.62 6-1.41 1-2.3 4.57-2.38 9.68 0 .94 0 2.43.1 4.15z" data-name="Path 3"></path>
                    <path fill="currentColor" d="M39.72 49.19c-4.81 0-16.32-5.65-16.32-19.65 0-9.95 1.26-19.66 16.32-19.66S56 19.59 56 29.54c0 14-11.48 19.65-16.28 19.65zM25.4 29.05v.49c0 12.57 10.1 17.65 14.32 17.65S54 42.11 54 29.54v-2.65c-7.7 1.64-13.39 1.31-16.93-1a7.09 7.09 0 01-2.62-2.83c-4.05.33-7.87 4.12-9.05 5.99zm9.57-8h1l.17.78c0 .05 1.78 6.48 17.78 3-.56-7.44-3.15-13-14.18-13-11.49 0-13.82 6-14.24 13.91 2.16-2.2 5.67-4.67 9.5-4.67zM67.88 53.53c-3.06 0-8.52-3.39-8.52-10.16 0-4.91.66-10.15 8.52-10.15s8.52 5.24 8.52 10.15c0 6.77-5.46 10.16-8.52 10.16zm-6.52-10.25v.09c0 5.73 4.66 8.16 6.52 8.16s6.52-2.43 6.52-8.16v-.67c-3.61.68-6.33.46-8.09-.67A4.26 4.26 0 0165 40.76a6.57 6.57 0 00-3.64 2.52zm5.25-3.78s.84 2.55 7.67 1.18c-.38-3.69-1.85-5.46-6.4-5.46-4.38 0-5.91 1.64-6.36 5.06a6.6 6.6 0 014.14-1.57h.79l.16.77z" data-name="Path 4"></path>
                    <path fill="none" d="M0 0h90.71v90.71H0z"></path></g>
                </svg>
                <span class="nav-text">@lang('Radnici')</span>
                </a>
            </li>

            <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
                    <svg data-chakra-component="CIcon" viewBox="22.82 10.4 63.45 70.17" role="presentation" class="css-5ffdf css-3cz70w css-0"><g>
                        <path fill="currentColor" d="M63.08 80.57H30.91a8.1 8.1 0 01-8.09-8.09V54l17.37-43.6h22.89a8.08 8.08 0 018.08 8.08v54a8.09 8.09 0 01-8.08 8.09zM24.82 54.4v18.08a6.09 6.09 0 006.09 6.09h32.17a6.09 6.09 0 006.08-6.09v-54a6.09 6.09 0 00-6.08-6.08H41.54z"></path>
                        <path fill="currentColor" d="M24.77 54.53l-1.89-.65c3.65-10.72 10.44-15.14 15.9-18.69 1.35-.87 2.62-1.7 3.78-2.57 2-1.47 2.62-3 2-4.68a19.28 19.28 0 00-2.43-4.33l-1-1.58C37.19 16 39.88 11.1 40 10.9l1.73 1c-.08.16-2.18 4 1.06 9.05.37.57.71 1.09 1 1.56a21.72 21.72 0 012.65 4.77c.89 2.51-.07 5-2.71 7-1.21.91-2.51 1.75-3.89 2.65-5.44 3.5-11.64 7.53-15.07 17.6zM31.44 68.06h30.45v2H31.44zM31.25 59.59H61.7v2H31.25zM31.25 51.12H61.7v2H31.25zM70.03 56.35h9.32v2h-9.32zM69.78 49.89h9.32v2h-9.32zM69.78 43.27h9.32v2h-9.32z"></path>
                        <path fill="currentColor" d="M81.45 68.12h-12.1v-2h12.1a2.82 2.82 0 002.82-2.82V29.39A2.44 2.44 0 0081.83 27H70.4v-2h11.43a4.44 4.44 0 014.44 4.44V63.3a4.82 4.82 0 01-4.82 4.82z"></path>
                        <path fill="none" d="M0 0h90.71v90.71H0z"></path>
                        </g>
                    </svg>
                    <span class="nav-text">@lang('Izlazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('customer-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>
            <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
                    <svg data-chakra-component="CIcon" viewBox="0 10.1 71.79 70.17" role="presentation" class="css-5ffdf css-3cz70w css-0"><g>
                        <path fill="currentColor" d="M63.7 80.27H31.53a8.08 8.08 0 01-8.08-8.08h2a6.09 6.09 0 006.08 6.08H63.7a6.09 6.09 0 006.09-6.08v-54a6.09 6.09 0 00-6.09-6.09H42.07L25.45 41.18v2.41h-2v-2.94L40.91 10.1H63.7a8.1 8.1 0 018.09 8.09v54a8.09 8.09 0 01-8.09 8.08z"></path>
                        <path fill="currentColor" d="M23.45 50.59h2v21.6h-2zM25.35 42.17l-1.81-.86c3.7-7.76 10.51-10.93 16-13.48 1.35-.63 2.62-1.22 3.78-1.85 2.64-1.41 2.12-2.48 1.94-2.83a13.49 13.49 0 00-2.37-3L41.83 19c-3.33-3.68-2.19-7.17-1.12-8.51l1.56 1.25c-.07.1-1.94 2.62 1 5.92l1 1.12A14.9 14.9 0 0147 22.26c.59 1.18 1 3.46-2.78 5.48-1.21.66-2.51 1.26-3.89 1.9-5.41 2.54-11.6 5.42-14.98 12.53zM32.06 67.76h30.45v2H32.06zM31.87 59.3h30.45v2H31.87zM8.59 52.21H0v-2h7.92l5.76-4.32A16.66 16.66 0 0120 43l.44 1.95a14.63 14.63 0 00-5.57 2.58z"></path>
                        <path fill="currentColor" d="M24.81 72.47h-4.19a11.55 11.55 0 01-6.23-1.82l-3.47-2.21H.49v-2h11l4 2.52a9.56 9.56 0 005.16 1.51h4.19zM36.22 52.12H20.14v-2h16.08a2.54 2.54 0 100-5.08H20.14V43h16.08a4.54 4.54 0 110 9.08z"></path>
                        <path fill="none" d="M.23 0h90.71v90.71H.23z"></path>
                        </g>
                    </svg>
                    <span class="nav-text">@lang('Ulazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('supplier-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>

            <hr>
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
                    <svg fill="#5a45aa" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M7 2L7 7L2 7L2 46L14 46L14 44L4 44L4 9L9 9L9 4L29 4L29 7L11 7L11 9L34 9L34 20L36 20L36 7L31 7L31 2 Z M 8 12L8 14L12 14L12 12 Z M 17 12L17 14L21 14L21 12 Z M 26 12L26 14L30 14L30 12 Z M 8 17L8 19L12 19L12 17 Z M 17 17L17 19L21 19L21 17 Z M 26 17L26 19L30 19L30 17 Z M 29 20C25.742188 20 23.328125 21.324219 22.074219 23.296875C20.929688 25.09375 20.875 27.339844 21.59375 29.433594C21.515625 29.566406 21.402344 29.679688 21.328125 29.839844C21.171875 30.191406 21.035156 30.589844 21.054688 31.097656L21.054688 31.101563C21.109375 32.378906 21.851563 33.046875 22.398438 33.421875C22.628906 34.640625 23.207031 35.660156 24 36.390625L24 38.53125C23.824219 38.953125 23.472656 39.308594 22.796875 39.679688C22.089844 40.070313 21.132813 40.4375 20.144531 40.917969C19.15625 41.398438 18.125 42.011719 17.324219 42.988281C16.519531 43.96875 16 45.308594 16 47L16 48L48.050781 48L47.992188 46.941406C47.902344 45.363281 47.316406 44.117188 46.488281 43.222656C45.664063 42.328125 44.644531 41.765625 43.679688 41.320313C42.714844 40.875 41.785156 40.535156 41.109375 40.171875C40.464844 39.832031 40.148438 39.511719 40 39.160156L40 37.472656C40.597656 36.609375 40.859375 35.617188 40.9375 34.6875C41.414063 34.265625 41.96875 33.617188 42.125 32.457031C42.230469 31.625 42.019531 30.996094 41.695313 30.464844C42.144531 29.277344 42.328125 27.84375 41.933594 26.417969C41.707031 25.589844 41.277344 24.777344 40.5625 24.171875C40.003906 23.691406 39.238281 23.425781 38.390625 23.308594L37.75 22L37.125 22C36.097656 22 35.085938 22.238281 34.214844 22.578125C33.871094 22.714844 33.558594 22.863281 33.265625 23.027344C33.101563 22.808594 32.921875 22.601563 32.714844 22.414063C32.105469 21.863281 31.261719 21.550781 30.324219 21.421875L29.621094 20 Z M 8 22L8 24L12 24L12 22 Z M 17 22L17 24L19.484375 24L20.761719 22 Z M 28.4375 22.113281L29.027344 23.300781L29.644531 23.300781C30.464844 23.300781 30.96875 23.535156 31.371094 23.894531C31.773438 24.257813 32.066406 24.796875 32.238281 25.429688C32.582031 26.695313 32.289063 28.339844 32.007813 28.792969L31.644531 29.371094L32.050781 29.921875C32.289063 30.238281 32.441406 30.566406 32.363281 31.007813C32.253906 31.625 32.03125 31.707031 31.589844 32.089844L31.257813 32.375L31.246094 32.8125C31.210938 33.792969 30.871094 34.777344 30.300781 35.339844L30 35.632813L30 38.988281L30.058594 39.152344C30.453125 40.25 31.335938 40.933594 32.234375 41.429688C33.132813 41.925781 34.101563 42.289063 34.976563 42.714844C35.851563 43.140625 36.609375 43.625 37.132813 44.261719C37.496094 44.699219 37.71875 45.289063 37.855469 46L18.144531 46C18.28125 45.289063 18.503906 44.699219 18.867188 44.261719C19.390625 43.625 20.148438 43.140625 21.023438 42.714844C21.898438 42.289063 22.867188 41.925781 23.765625 41.429688C24.664063 40.933594 25.546875 40.25 25.941406 39.152344L26 38.988281L26 35.523438L25.5625 35.226563C25.101563 34.914063 24.34375 33.769531 24.238281 32.742188L24.183594 32.1875L23.683594 31.945313C23.398438 31.808594 23.082031 31.753906 23.050781 31.015625C23.050781 31.015625 23.082031 30.824219 23.15625 30.65625C23.234375 30.484375 23.375 30.304688 23.332031 30.347656L23.8125 29.867188L23.542969 29.242188C22.796875 27.523438 22.898438 25.722656 23.761719 24.367188C24.550781 23.125 26.097656 22.269531 28.4375 22.113281 Z M 36.558594 24.113281L37.089844 25.199219L37.714844 25.199219C38.472656 25.199219 38.921875 25.398438 39.265625 25.691406C39.613281 25.984375 39.859375 26.414063 40.003906 26.949219C40.300781 28.019531 40.085938 29.480469 39.746094 30.144531L39.417969 30.796875L39.933594 31.308594C39.867188 31.242188 40.195313 31.785156 40.140625 32.195313C40.011719 33.175781 39.871094 33.113281 39.449219 33.390625L39.03125 33.667969L39 34.171875C38.953125 35.042969 38.515625 36.351563 38.28125 36.589844L38 36.878906L38 39.621094L38.058594 39.78125C38.4375 40.835938 39.296875 41.476563 40.167969 41.9375C41.035156 42.398438 41.980469 42.738281 42.84375 43.136719C43.707031 43.535156 44.476563 43.984375 45.019531 44.578125C45.367188 44.953125 45.601563 45.433594 45.769531 46L39.921875 46C39.757813 44.777344 39.3125 43.765625 38.675781 42.988281C37.875 42.011719 36.84375 41.398438 35.855469 40.917969C34.867188 40.4375 33.910156 40.070313 33.203125 39.679688C32.527344 39.308594 32.175781 38.953125 32 38.53125L32 36.296875C32.691406 35.421875 33.054688 34.390625 33.15625 33.34375C33.542969 33.003906 34.144531 32.417969 34.332031 31.359375C34.484375 30.492188 34.226563 29.785156 33.90625 29.210938C34.4375 27.988281 34.59375 26.460938 34.167969 24.902344C34.164063 24.886719 34.15625 24.871094 34.152344 24.855469C34.367188 24.71875 34.640625 24.5625 34.949219 24.441406C35.4375 24.25 36.007813 24.179688 36.558594 24.113281 Z M 8 27L8 29L12 29L12 27 Z M 17 27L17 29L19.753906 29L19.394531 27 Z M 8 32L8 34L12 34L12 32 Z M 17 32L17 34L20.449219 34L19.613281 32 Z M 8 37L8 39L12 39L12 37 Z M 17 37L17 39L21 39L21 37Z"></path></g></svg>
                    <span class="nav-text">@lang('Firme')</span>
                </a>
                <ul aria-expanded="true" class="left mm-show">
                    <li class="nav-text-icon-toggle">@lang('Firme')</li>
                    <li><a class="dz-active" href="{{ route("firme.index") }}">@lang('Lista firmi')</a></li>
                    <li><a class="dz-active" href="{{ route('firme.create') }}">+ @lang('Kreiraj firmu')</a></li>
                </ul>
            </li>
            <hr>
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
                    <i class="flaticon-notes text-primary"></i>
                    <span class="nav-text">@lang('Racuni')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">Racuni</li>
                    <li><a class="dz-active" href="{{ route("rechnung.index") }}">@lang('Lista racuna')</a></li>
                    <li><a class="dz-active" href="{{ route('rechnung.index') }}?openModal=1">+ @lang('Kreiraj racun')</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
                    <i class="flaticon-notes text-primary"></i>
                    <span class="nav-text">@lang('Ponude')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">@lang('Ponude')</li>
                    <li><a class="dz-active" href="{{ route("angebote.index") }}">@lang('Lista ponuda')</a></li>
                    <li><a class="dz-active" href="{{ route('angebote.index') }}?openModal=1">+ @lang('Kreiraj ponudu')</a></li>
                </ul>
            </li>


            {{-- <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-layout"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Dashboard</li>
                    <li><a class="dz-active" href="files/index.html">Dashboard Light</a></li>
                    <li><a class="dz-active" href="files/index-2.html">Dashboard Dark</a></li>
                    <li><a class="dz-active" href="files/wallet.html">Wallet</a></li>
                    <li><a class="dz-active" href="files/invoices.html">Invoices</a></li>
                    <li><a class="dz-active" href="files/create-invoices.html">Create Invoice</a></li>
                    <li><a class="dz-active" href="files/card-center.html">Card Center</a></li>
                    <li><a class="dz-active" href="files/transaction-details.html">Transaction</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-user-1"></i>
                    <span class="nav-text">Profile</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/profile/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/profile/projects.html">Projects</a></li>
                    <li><a class="dz-active" href="files/profile/projects-details.html">Projects Details</a></li>
                    <li><a class="dz-active" href="files/profile/campaigns.html">Campaigns</a></li>
                    <li><a class="dz-active" href="files/profile/documents.html">Documents</a></li>
                    <li><a class="dz-active" href="files/profile/followers.html">Followers</a></li>
                    <li><a class="dz-active" href="files/profile/activity.html">Activity</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-app"></i>
                    <span class="nav-text">Account</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/account/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/account/settings.html">Settings</a></li>
                    <li><a class="dz-active" href="files/account/security.html">Security</a></li>
                    <li><a class="dz-active" href="files/account/activity.html">Activity</a></li>
                    <li><a class="dz-active" href="files/account/billing.html">Billing</a></li>
                    <li><a class="dz-active" href="files/account/statements.html">Statements</a></li>
                    <li><a class="dz-active" href="files/account/referrals.html">Referrals</a></li>
                    <li><a class="dz-active" href="files/account/api-keys.html">Api keys</a></li>
                    <li><a class="dz-active" href="files/account/logs.html">Logs</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web-1"></i>
                    <span class="nav-text">CMS</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">CMS</li>
                    <li><a class="dz-active" href="files/content.html">Content</a></li>
                    <li><a class="dz-active" href="files/content-add.html">Add Content</a></li>
                    <li><a class="dz-active" href="files/menu.html">Menus</a></li>
                    <li><a class="dz-active" href="files/email-template.html">Email Template</a></li>
                    <li><a class="dz-active" href="files/add-email.html">Add Email</a></li>
                    <li><a class="dz-active" href="files/blog.html">Blog</a></li>
                    <li><a class="dz-active" href="files/add-blog.html">Add Blog</a></li>
                    <li><a class="dz-active" href="files/blog-category.html">Blog Category</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chip"></i>
                    <span class="nav-text">AIKit</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">AIKit</li>
                    <li><a class="dz-active" href="files/auto-write.html">Auto Writer</a></li>
                    <li><a class="dz-active" href="files/scheduled.html">Scheduler</a></li>
                    <li><a class="dz-active" href="files/repurpose.html">Repurpose</a></li>
                    <li><a class="dz-active" href="files/rss.html">RSS</a></li>
                    <li><a class="dz-active" href="files/chatbot.html">Chatbot</a></li>
                    <li><a class="dz-active" href="files/fine-tune-models.html">Fine-tune Models</a></li>
                    <li><a class="dz-active" href="files/prompt.html">AI Menu Prompts</a></li>
                    <li><a class="dz-active" href="files/setting.html">Settings</a></li>
                    <li><a class="dz-active" href="files/import.html">Export/Import Settings</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-blog"></i>
                    <span class="nav-text">Blog</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Blog</li>
                    <li><a class="dz-active" href="files/blog-post.html">Blog Post</a></li>
                    <li><a class="dz-active" href="files/blog-home.html">Blog Home</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-user"></i>
                    <span class="nav-text">User</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">User</li>
                    <li><a class="dz-active" href="files/app-profile.html">Profile</a></li>
                    <li><a class="dz-active" href="files/edit-profile.html">Edit Profile</a></li>
                    <li><a class="dz-active" href="files/post-details.html">Post Details</a></li>


                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bag"></i>
                    <span class="nav-text">jobs</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">jobs</li>
                    <li><a class="dz-active" href="files/job-view.html">Job View</a></li>
                    <li><a class="dz-active" href="files/job-application.html">Job Application</a></li>
                    <li><a class="dz-active" href="files/apply-job.html">Apply Job</a></li>
                    <li><a class="dz-active" href="files/new-job.html">New Job</a></li>

                </ul>
            </li>

            <li><a href="files/pricing.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-price-tag"></i>
                    <span class="nav-text">Pricing</span>
                </a>
            </li>
            <li class="menu-title">Apps</li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chat"></i>
                    <span class="nav-text">Chat</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Chat</li>
                    <li><a class="dz-active" href="files/chat-home.html">Chat Home</a></li>
                    <li><a class="dz-active" href="files/chat-modal.html">Chat Modal</a></li>

                </ul>
            </li>
            <li><a href="files/kanban.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-kanban"></i>
                    <span class="nav-text">Kanban</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">

                    <i class="flaticon-email"></i>
                    <span class="nav-text">Email</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Email</li>
                    <li><a class="dz-active" href="files/email-compose.html">Compose</a></li>
                    <li><a class="dz-active" href="files/email-inbox.html">Inbox</a></li>
                    <li><a class="dz-active" href="files/email-read.html">Read</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-shopping-bag"></i>

                    <span class="nav-text">Ecommerce</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Ecommerce</li>
                    <li><a class="dz-active" href="files/ecommerce-dashboard.html">Dashboard</a></li>
                    <li><a class="dz-active" href="files/ecommerce-setting.html">Setting</a></li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Categories</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Categories</li>
                            <li><a class="dz-active" href="files/category-table.html">Category Table</a></li>
                            <li><a class="dz-active" href="files/add-categary.html">Add Category</a></li>
                            <li><a class="dz-active" href="files/edit-categary.html">Edit Category</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Products</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Products</li>
                            <li><a class="dz-active" href="files/product-table.html">Product Table</a></li>
                            <li><a class="dz-active" href="files/add-product.html">Add product</a></li>
                            <li><a class="dz-active" href="files/edit-product.html">Edit Product</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Shop</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Shop</li>
                            <li><a class="dz-active" href="files/ecom-product-grid.html">Product Grid</a></li>
                            <li><a class="dz-active" href="files/ecom-product-list.html">Product List</a></li>
                            <li><a class="dz-active" href="files/ecom-product-detail.html">Product Details</a></li>
                            <li><a class="dz-active" href="files/ecom-product-order.html">Order</a></li>
                            <li><a class="dz-active" href="files/ecom-checkout.html">Checkout</a></li>
                            <li><a class="dz-active" href="files/ecom-invoice.html">Invoice</a></li>
                            <li><a class="dz-active" href="files/ecom-customers.html">Customers</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-rocket"></i>
                    <span class="nav-text">Projects</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Projects</li>
                    <li><a class="dz-active" href="files/project-list.html">Project List</a></li>
                    <li><a class="dz-active" href="files/project-card.html">Project Card</a></li>
                    <li><a class="dz-active" href="files/add-project.html">Add Project</a></li>

                </ul>
            </li>
            <li><a href="files/note.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-notes"></i>
                    <span class="nav-text">Notes</span>
                </a>
            </li>
            <li><a href="files/task.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-checkmark"></i>
                    <span class="nav-text">Task</span>
                </a>
            </li>
            <li><a href="files/file-manger.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-approved"></i>
                    <span class="nav-text">File Manager</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-phone-book"></i>
                    <span class="nav-text">Contacts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Contacts</li>
                    <li><a class="dz-active" href="files/contact-list.html">Contact List</a></li>
                    <li><a class="dz-active" href="files/contact-card.html">Contact Card</a></li>
                </ul>
            </li>

            <li><a href="files/app-calender.html" class="" aria-expanded="false">
                    <i class="flaticon-calendar-2"></i>
                    <span class="nav-text">Calender</span>

                </a>
            </li>
            <li class="menu-title">Components</li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bar-chart"></i>
                    <span class="nav-text">Charts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Charts</li>
                    <li><a class="dz-active" href="files/apex.html">Apex Chart</a></li>
                    <li><a class="dz-active" href="files/chart-flot.html">Flot</a></li>
                    <li><a class="dz-active" href="files/chart-morris.html">Morris</a></li>
                    <li><a class="dz-active" href="files/chart-chartjs.html">Chartjs</a></li>
                    <li><a class="dz-active" href="files/chart-chartist.html">Chartist</a></li>
                    <li><a class="dz-active" href="files/chart-sparkline.html">Sparkline</a></li>
                    <li><a class="dz-active" href="files/chart-peity.html">Peity</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web"></i>
                    <span class="nav-text">Bootstrap</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Bootstrap</li>
                    <li><a class="dz-active" href="files/ui-accordion.html">Accordion</a></li>
                    <li><a class="dz-active" href="files/ui-alert.html">Alert</a></li>
                    <li><a class="dz-active" href="files/ui-badge.html">Badge</a></li>
                    <li><a class="dz-active" href="files/ui-button.html">Button</a></li>
                    <li><a class="dz-active" href="files/ui-modal.html">Modal</a></li>
                    <li><a class="dz-active" href="files/ui-button-group.html">Button Group</a></li>
                    <li><a class="dz-active" href="files/ui-list-group.html">List Group</a></li>
                    <li><a class="dz-active" href="files/ui-card.html">Cards</a></li>
                    <li><a class="dz-active" href="files/ui-carousel.html">Carousel</a></li>
                    <li><a class="dz-active" href="files/ui-dropdown.html">Dropdown</a></li>
                    <li><a class="dz-active" href="files/ui-popover.html">Popover</a></li>
                    <li><a class="dz-active" href="files/ui-progressbar.html">Progressbar</a></li>
                    <li><a class="dz-active" href="files/ui-tab.html">Tab</a></li>
                    <li><a class="dz-active" href="files/ui-typography.html">Typography</a></li>
                    <li><a class="dz-active" href="files/ui-pagination.html">Pagination</a></li>
                    <li><a class="dz-active" href="files/ui-grid.html">Grid</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-puzzle"></i>
                    <span class="nav-text">Plugins</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Plugins</li>
                    <li><a class="dz-active" href="files/uc-select2.html">Select 2</a></li>
                    <li><a class="dz-active" href="files/uc-nestable.html">Nestedable</a></li>
                    <li><a class="dz-active" href="files/uc-noui-slider.html">Noui Slider</a></li>
                    <li><a class="dz-active" href="files/uc-tree.html">Tree View</a></li>
                    <li><a class="dz-active" href="files/uc-star-rating.html">Star Rating</a></li>
                    <li><a class="dz-active" href="files/uc-drag-and-drop.html">Drag And Drop</a></li>
                    <li><a class="dz-active" href="files/uc-media-player.html">Media Player</a></li>
                    <li><a class="dz-active" href="files/uc-sweetalert.html">Sweet Alert</a></li>
                    <li><a class="dz-active" href="files/uc-toastr.html">Toastr</a></li>
                    <li><a class="dz-active" href="files/map-jqvmap.html">Jqv Map</a></li>
                    <li><a class="dz-active" href="files/uc-lightgallery.html">Light Gallery</a></li>
                </ul>
            </li>
            <li><a href="files/widget-basic.html" class="" aria-expanded="false">
                    <i class="flaticon-app"></i>
                    <span class="nav-text">Widget</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-registration"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Forms</li>
                    <li><a class="dz-active" href="files/form-element.html">Form Elements</a></li>
                    <li><a class="dz-active" href="files/form-wizard.html">Wizard</a></li>
                    <li><a class="dz-active" href="files/form-ckeditor.html">CkEditor</a></li>
                    <li><a class="dz-active" href="files/form-pickers.html">Pickers</a></li>
                    <li><a class="dz-active" href="files/form-validation.html">Form Validate</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-grid"></i>
                    <span class="nav-text">Table</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Table</li>
                    <li><a class="dz-active" href="files/table-bootstrap-basic.html">Bootstrap</a></li>
                    <li><a class="dz-active" href="files/table-datatable-basic.html">Datatable</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-file"></i>
                    <span class="nav-text">Pages</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li><a class="dz-active" href="files/page-login.html">Login</a></li>
                    <li><a class="dz-active" href="files/page-register.html">Register</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Pages</li>
                            <li><a class="dz-active" href="files/page-error-400.html">Error 400</a></li>
                            <li><a class="dz-active" href="files/page-error-403.html">Error 403</a></li>
                            <li><a class="dz-active" href="files/page-error-404.html">Error 404</a></li>
                            <li><a class="dz-active" href="files/page-error-500.html">Error 500</a></li>
                            <li><a class="dz-active" href="files/page-error-503.html">Error 503</a></li>
                        </ul>
                    </li>
                    <li><a class="dz-active" href="files/page-lock-screen.html">Lock Screen</a></li>
                    <li><a class="dz-active" href="files/empty-page.html">Empty Page</a></li>
                </ul>
            </li> --}}

        </ul>

        <div style="display: none;" class="copyright">
            <p><strong>Kubayar Invoicing Admin Dashboard</strong> © <span class="current-year">2024</span> All
                Rights Reserved</p>
            <p class="fs-12">Made with <span class="heart"></span> by DexignZone</p>
        </div>
    </div>
</div>
