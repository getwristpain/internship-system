<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Informasi Manajemen PKL - SMK Negeri 2 Klaten</title>

    <link rel="shortcut icon" href="" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-slate-
100">
    <div class="flex w-full min-h-screen">
        <!-- Page Sidebar -->
        @livewire('components.sidebar')

        <div class="flex flex-col w-full">
            <!-- Page Sidebar -->
            @livewire('components.navbar')

            <!-- Page Content -->
            <main {{ $attributes->merge(['class' => 'p-4']) }}>
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @livewireScripts
</body>

</html>
