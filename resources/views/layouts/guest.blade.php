<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Informasi Manajemen PKL' }}{{ isset($brand) ? ' - ' . $brand : '' }}</title>

    <link rel="shortcut icon" href="{{ $logo ?? (asset('img/logo.png') ?? '') }}" type="image/x-icon">

    <!-- Vite Load -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/vendor.js'])

    @livewireStyles
</head>

<body class="flex flex-col flex-grow">
    <header class="sticky top-0 z-10 w-full px-8 border-b bg-base-100">
        <div class="navbar bg-base-100">
            <div class="navbar-start">
                <x-brand />
            </div>
        </div>
    </header>

    <main class="flex items-center justify-center flex-1 px-12 pt-4 pb-12">
        {{ $slot }}
    </main>

    @stack('scripts')
    @livewireScripts
    @livewireChartsScripts
</body>

</html>
