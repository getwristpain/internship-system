<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Sistem Informasi Manajemen PKL - SMK Negeri 2 Klaten' }}</title>

        <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles()
    </head>

    <body class="flex flex-col">

        {{-- Header --}}
        <header class="sticky top-0 z-50 w-full px-8 border-b bg-base-100">
            {{-- Navbar --}}
            <div class="navbar bg-base-100">
                <div class="navbar-start">
                    <x-brand />
                </div>
            </div>
        </header>

        <main class="flex items-center justify-center w-full h-full grow">
            <div class="container p-8 mx-auto">
                {{ $slot }}
            </div>
        </main>

        <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

        @stack('scripts')
        @livewireScripts()
    </body>

</html>
