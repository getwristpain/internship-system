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

<body class="flex flex-col h-screen overflow-x-auto">
    <header class="flex justify-between items-center px-8 py-4 w-full max-w-screen bg-white">
        <x-brand />
        <nav class="flex gap-4 items-center">
            @switch(request()->route()->getName())
                @case('login')
                    <x-btn-tertiary>Belum punya akun?</x-btn-tertiary>
                    <x-btn-secondary>Register</x-btn-secondary>
                @break

                @case('register')
                    <x-btn-tertiary>Sudah punya akun?</x-btn-tertiary>
                    <x-btn-secondary>Login</x-btn-secondary>
                @break

                @default
            @endswitch
        </nav>
    </header>

    <main class="grow flex w-full h-full justify-center items-center">
        {{ $slot }}
    </main>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @livewireScripts()
</body>

</html>
