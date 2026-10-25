<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

@php
    $companyName = \App\Models\Setting::get('company_name', config('app.name', 'POS'));
    $primaryColor = \App\Models\Setting::get('primary_color', '#FFA500');
    $loginBg = \App\Models\Setting::get('login_background');
    $bgUrl = $loginBg && file_exists(public_path('storage/' . $loginBg))
        ? asset('storage/' . $loginBg)
        : (file_exists(public_path('assets/images/restaurant-bg.jpeg'))
            ? asset('assets/images/restaurant-bg.jpeg')
            : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=2000');
    $logo = \App\Models\Setting::get('system_logo');
    $favicon = \App\Models\Setting::get('system_favicon');
    $loginFilter = \App\Models\Setting::get('login_backdrop_filter', 'blur(6px) brightness(0.65)');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $companyName }}</title>

    <!-- Favicon -->
    @if($favicon && file_exists(public_path('storage/' . $favicon)))
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        'primary-hover': '{{ $primaryColor }}',
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full relative overflow-hidden flex items-center justify-center font-['Poppins'] text-gray-800 dark:text-gray-100 select-none">

    <!-- Background Image Container -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat scale-105 transform transition-transform duration-1000"
         style="background-image: url('{{ $bgUrl }}'); {{ $loginFilter !== 'none' ? 'filter: ' . $loginFilter . '; -webkit-filter: ' . $loginFilter . ';' : '' }}">
    </div>

    <!-- Backdrop Filter Overlay (Back Filter) -->
    <div class="absolute inset-0 z-0 bg-gradient-to-tr from-gray-950/80 via-black/55 to-gray-900/65 pointer-events-none"
         style="{{ $loginFilter !== 'none' ? 'backdrop-filter: ' . $loginFilter . '; -webkit-backdrop-filter: ' . $loginFilter . ';' : '' }}"></div>

    <!-- Login Glassmorphic Card -->
    <div class="relative z-10 w-full max-w-md mx-4 p-8 sm:p-10 rounded-3xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-2xl border border-white/40 dark:border-gray-700/60 transition-all">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            @if($logo && file_exists(public_path('storage/' . $logo)))
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $companyName }}" class="h-20 max-w-[200px] mx-auto mb-4 object-contain">
            @else
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ $companyName }}" class="h-20 max-w-[200px] mx-auto mb-4 object-contain">
            @endif

            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $companyName }}
            </h2>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">
                Acesso ao Sistema
            </p>
        </div>

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2.5 shadow-sm" role="alert">
                <i class="mdi mdi-alert-circle text-lg flex-shrink-0"></i>
                <p class="font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5 pl-1">
                    Utilizador / E-mail
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-account-outline text-lg"></i>
                    </span>
                    <input type="text" id="username" name="username" placeholder="Digite seu usuário..."
                        class="w-full pl-11 pr-4 py-3 rounded-2xl bg-gray-50/90 dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 shadow-2xs"
                        value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5 pl-1">
                    Palavra-passe
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-lock-outline text-lg"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha..."
                        class="w-full pl-11 pr-4 py-3 rounded-2xl bg-gray-50/90 dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 shadow-2xs"
                        required>
                </div>
            </div>

            <!-- Submit Button with Dynamic Primary Color -->
            <div class="pt-2">
                <button type="submit"
                    style="background-color: {{ $primaryColor }};"
                    class="w-full text-white font-bold py-3.5 px-6 rounded-2xl transform hover:-translate-y-0.5 active:translate-y-0 transition duration-200 shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                    <i class="mdi mdi-login text-lg"></i>
                    <span>Entrar no Sistema</span>
                </button>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>&copy; {{ date('Y') }} {{ $companyName }}</span>
            <span class="text-[11px] opacity-75">v1.0.0</span>
        </div>
    </div>

    <script>
        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</body>

</html>
