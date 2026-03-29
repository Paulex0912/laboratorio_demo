<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- PWA Settings -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#6B46C1">
        <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((reg) => console.log('Service Worker registrado', reg))
                        .catch((err) => console.log('Registro SW falló', err));
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased text-gray-800 bg-[#f4f7fb]" style="font-family: 'Inter', sans-serif;">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden bg-[#f4f7fb]">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header Navigation -->
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f4f7fb]">
                    @isset($header)
                        <div class="container mx-auto px-6 py-4">
                            {{ $header }}
                        </div>
                    @endisset

                    <div class="container mx-auto px-6 py-4">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
