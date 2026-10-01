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

        <style>[x-cloak] { display: none !important; }</style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <!-- Default: terbuka di desktop, tertutup di HP. Kirim event resize agar DataTable menghitung ulang lebar -->
        <div x-data="{ open: window.innerWidth >= 1024 }"
             x-init="$watch('open', () => setTimeout(() => window.dispatchEvent(new Event('resize')), 350))"
             class="min-h-screen bg-gray-100 flex relative overflow-x-hidden">

            <!-- Backdrop, hanya di HP -->
            <div x-show="open" x-cloak x-transition.opacity @click="open = false"
                 class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

            @include('layouts.navigation')

            <!-- Sidebar mendorong konten hanya di desktop (lg), di HP tampil sebagai overlay -->
            <div class="flex-1 flex flex-col min-w-0 ml-0 transition-all duration-300"
                 :class="open ? 'lg:ml-64' : ''">

                <header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6">
                    <div class="flex items-center">
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
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                        <div class="text-right hidden sm:block">
                            <div class="font-medium text-sm text-gray-800 leading-tight">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                            alt="{{ Auth::user()->name }}">
                    </a>
                </header>

                <main class="flex-1 p-3 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>