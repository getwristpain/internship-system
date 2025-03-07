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

<body class="flex w-full min-h-screen bg-gray-50">
    <!-- Page Sidebar -->
    @livewire('components.sidebar')

    <div class="flex flex-col w-full min-h-screen overflow-y-auto">
        <!-- Page Navbar -->
        @livewire('components.navbar')

        <!-- Page Content -->
        <main class="p-4 grow">
            {{ $slot }}
        </main>
    </div>

    @livewire('components.notification')

    @stack('scripts')
    @livewireScripts
    @livewireChartsScripts
</body>

</html>
