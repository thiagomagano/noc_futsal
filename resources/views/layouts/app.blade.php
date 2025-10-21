<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Futsal Manager')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-base-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <div class="navbar bg-base-400 shadow-lg">

            @auth
                <div class="navbar-start hidden lg:flex">
                    <a href="{{ route('dashboard') }}" class="text-3xl font-bold">
                        <img src="{{ asset('images/logo_noc.png') }}"
                            alt="Logo do time, escudo vermelho e branco com uma estrela vermelha no centro"
                            class="w-16 h-16 object-contain hover:scale-105 transition-all duration-200">

                    </a>
                </div>

                <div class="navbar-center hidden lg:flex">
                    <ul class="menu menu-horizontal px-1">

                        <li>
                            <a href="{{ route('atletas.index') }}"
                                class="{{ request()->routeIs('atletas.*') ? 'active' : '' }}">
                                Atletas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('partidas.index') }}"
                                class="{{ request()->routeIs('partidas.*') ? 'active' : '' }}">
                                Partidas
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-end">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline text-xs">Sair</button>

                    </form>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="lg:hidden">
                <div class="btm-nav btm-nav-sm bg-primary text-primary-content">
                    <a href="{{ route('atletas.index') }}" class="{{ request()->routeIs('atletas.*') ? 'active' : '' }}">
                        <span class="btm-nav-label">Atletas</span>
                    </a>
                    <a href="{{ route('partidas.index') }}"
                        class="{{ request()->routeIs('partidas.*') ? 'active' : '' }}">
                        <span class="btm-nav-label">Partidas</span>
                    </a>
                </div>
            </div>
        @endauth

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 pb-20 lg:pb-6">
            <!-- Breadcrumb -->
            @if (View::hasSection('breadcrumb'))
                <div class="text-sm breadcrumbs mb-6">
                    <ul>
                        @yield('breadcrumb')
                    </ul>
                </div>
            @endif

            <!-- Page Header -->
            @if (View::hasSection('header'))
                <div class="mb-6">
                    @yield('header')
                </div>
            @endif


            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold">Corrija os erros abaixo:</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
    @stack('scripts')

</body>

</html>
