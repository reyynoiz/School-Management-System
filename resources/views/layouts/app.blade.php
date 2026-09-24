<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Inisialisasi state Alpine.js (default: open) -->
        <div x-data="{ open: true }" class="min-h-screen bg-gray-100 flex relative overflow-x-hidden">
            
            <!-- Partial Sidebar -->
            @include('layouts.navigation')

            <!-- Main Layout Wrapper (Otomatis geser saat sidebar dibuka/ditutup) -->
            <div class="flex-1 flex flex-col min-w-0 transition-all duration-300" :class="open ? 'ml-64' : 'ml-0'">
                
                <!-- Header Atas (Tempat Tombol Buka Menu Sidebar) -->
                <header class="bg-white border-b border-gray-100 h-16 flex items-center px-4 sm:px-6">
                    <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    @isset($header)
                        <div class="ml-4 font-semibold text-xl text-gray-800 leading-tight">
                            {{ $header }}
                        </div>
                    @endisset
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>