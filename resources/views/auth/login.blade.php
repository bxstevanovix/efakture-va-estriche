{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- PAGE TITLE HERE -->
	<title>E-faktura</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">

	<meta name="keywords" content="admin, admin dashboard, admin template, analytics, bootstrap, bootstrap5, bootstrap 5 admin template, modern, responsive admin dashboard, sales dashboard, Invoicing, ui kit, web app, Kubayar Billing, User Interface (UI), User Experience (UX), Dashboard Design, Invoice Management, Web Application, Data Visualization, Analytics, Customization, Responsive Design, Bootstrap Framework, Charts and Graphs, Data Management, Reporting, Dark Mode, Mobile-Friendly, Dashboard Components, Integrations, Analytics Dashboard, API Integration, User Authentication">


	<meta name="description" content="Streamline your invoicing tasks with the Kubayar Invoicing Admin Dashboard. This user-friendly interface offers robust features and customizable options, making it ideal for managing your data efficiently. With intuitive controls for transactions and insightful reporting, Kubayar enhances productivity, ensuring smooth invoicing operations for your business.">

	<meta property="og:title" content="Kubayar Invoicing Admin Dashboard | DexignZone">
	<meta property="og:description" content="Streamline your invoicing tasks with the Kubayar Invoicing Admin Dashboard. This user-friendly interface offers robust features and customizable options, making it ideal for managing your data efficiently. With intuitive controls for transactions and insightful reporting, Kubayar enhances productivity, ensuring smooth invoicing operations for your business.">
	<meta property="og:image" content="https://Kubayar.dexignzone.com/xhtml/social-image.png">
	<meta name="format-detection" content="telephone=no">

	<meta name="twitter:title" content="Kubayar Invoicing Admin Dashboard | DexignZone">
	<meta name="twitter:description" content="Streamline your invoicing tasks with the Kubayar Invoicing Admin Dashboard. This user-friendly interface offers robust features and customizable options, making it ideal for managing your data efficiently. With intuitive controls for transactions and insightful reporting, Kubayar enhances productivity, ensuring smooth invoicing operations for your business.">
	<meta name="twitter:image" content="https://Kubayar.dexignzone.com/xhtml/social-image.png">
	<meta name="twitter:card" content="summary_large_image">

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v=14">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('f-circle.svg') }}">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="{{ asset('f-circle.svg') }}">
	<meta name="application-name" content="VA Estriche">
	<meta name="apple-mobile-web-app-title" content="VA Estriche">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="theme-color" content="#5746A3">
	
	<!-- FAVICONS ICON -->
    <link rel="icon" href="{{ asset('f-circle.svg') }}" type="image/svg+xml">
	<link href="{{ asset('files/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
	<link href="{{ asset('files/css/style.css') }}" rel="stylesheet">

    <style>
    @media (max-width: 765px) {
        input,
        textarea,
        select,
        .form-control {
            font-size: 16px !important;
        }

        .auth-form input.form-control {
            font-size: 16px !important;
            line-height: 24px !important;
        }
    }
    </style>

</head>

<body>
    <div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
		<div class="login-aside text-center d-none d-sm-flex flex-column flex-row-auto">
			<div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
				<div class="text-center mb-4 pt-5">
					<a><img width="420px" src="{{ asset('img/e-fakture-logo.jpg') }}" class="dark-login"  alt="E-fakture"></a>
					<a><img width="420px" src="{{ asset('img/e-fakture-logo.jpg') }}" class="light-login" alt="E-fakture"></a>
				</div>
				{{-- <h3 style="font-size: 34px;" class="mb-2">E-FAKTURE</h3> --}}
				<p>Easily create invoices, track your clients, and optimize your processes.<br> <strong>E-fakture</strong> gives you complete control over your business. <br> Learn more at <a style="color: var(--primary);" href="https://fakture.at" target="_blank">fakture.at</a></p>
			</div>
            <div class="aside-image" style="background-image:url('{{ asset('files/images/pic1.svg') }}');"></div>
		</div>
		<div class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
			<div class="d-flex justify-content-center h-100 align-items-center">
				<div class="authincation-content style-2">
                    <div class="row no-gutters">
						<div class="col-xl-12">
							<div class="auth-form">
								<div class="text-center d-block d-lg-none mb-4 pt-5">
									<a><img width="80%" src="{{ asset('img/e-fakture-logo.jpg') }}" class="dark-login"  alt="E-fakture"></a>
									<a><img width="80%" src="{{ asset('img/e-fakture-logo.jpg') }}" class="light-login" alt="E-fakture"></a>
								</div>
								
								<h4 class="text-center mb-4">Sign in your account</h4>
                                
								<form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    @if ($errors->any())
                                        <div class="alert alert-danger mb-4">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session('status'))
                                        <div class="alert alert-success mb-4">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <label class="mb-1 form-label" for="email">{{ __('Email') }}</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control @error('email') is-invalid @enderror">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="mb-1 form-label" for="password">{{ __('Password') }}</label>
                                        <div class="position-relative">
                                            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                                            <span class="show-pass eye">
                                                <i class="fa fa-eye-slash"></i>
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="remember" id="remember_me" class="form-check-input">
                                        <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">{{ __('Log in') }}</button>
                                    </div>

                                    {{-- Password reset link disabled.
                                    @if (Route::has('password.request'))
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                        </div>
                                    @endif
                                    --}}
                                </form>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('files/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('files/vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('files/js/custom.min.js') }}"></script>
    <script src="{{ asset('files/js/deznav-init.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePass = document.querySelectorAll('.show-pass.eye');

        togglePass.forEach(function(span) {
            const input = span.previousElementSibling; // input password
            const eyeSlash = span.querySelector('.fa-eye-slash');
            const eye = span.querySelector('.fa-eye');

            // inicijalno sakrij "eye"
            eye.style.display = 'none';

            span.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    eye.style.display = 'inline';
                    eyeSlash.style.display = 'none';
                } else {
                    input.type = 'password';
                    eye.style.display = 'none';
                    eyeSlash.style.display = 'inline';
                }
            });
        });
    });
    </script>
	
</body>
</html>
