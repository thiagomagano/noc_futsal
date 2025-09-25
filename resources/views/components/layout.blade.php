<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        </style>
    @endif
</head>

<body>
    <header class="w-full mb-6 ">
        <nav class="navbar bg-base-700 shadow-sm">
            <div class="flex-1">
                <a href={{ route('dashboard') }} class="btn btn-ghost text-xl">N.O.C</a>
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1">
                    <li><a href={{ route('atletas.index') }}>Jogadores</a></li>
                    <li>
                        <details>
                            <summary>Partida</summary>
                            <ul class="bg-base-100 rounded-t-none p-2">
                                <li><a>Organizar</a></li>
                                <li><a>Histórico</a></li>
                            </ul>
                        </details>
                    </li>
                    <li>

                    </li>
                </ul>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost text-sm">Sair</button>
            </form>
        </nav>
    </header>
    {{ $slot }}
</body>

</html>
