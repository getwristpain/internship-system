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

    @livewireStyles()
</head>

<body class="flex flex-col h-screen overflow-y-auto">
    <header class="fixed lg:static flex justify-between items-center px-8 py-4 w-full max-w-screen bg-white">
        <x-brand />
        <nav class="flex gap-4 items-center">
            @switch(request()->route()->getName())
                @case('login')
                    <span>Belum punya akun?</span>
                    <x-button-secondary href="{{ route('register') }}">Register</x-button-secondary>
                @break

                @case('register')
                    <span>Sudah punya akun?</span>
                    <x-button-secondary href="{{ route('login') }}">Login</x-button-secondary>
                @break

                @default
            @endswitch
        </nav>
    </header>

    <main class="grow w-full h-full pt-16 lg:pt-0">
        {{ $slot }}
    </main>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @stack('scripts')
    @livewireScripts()
</body>

</html>
