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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @auth
                @include('layouts.sidebar')
                <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden"></div>
            @endauth

            <div class="flex-1 flex flex-col @auth md:ml-64 @endauth">
                @auth
                    <header class="bg-white shadow md:hidden">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                            <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <h1 class="text-xl font-semibold">Bureau De Change</h1>
                            <div></div> <!-- To balance the flexbox -->
                        </div>
                    </header>
                @endauth

                {{-- Flash messages --}}
                <x-flash/>

                <!-- Page Content -->
                <main class="py-10 flex-1">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
        @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.getElementById('sidebar');
                const toggleButton = document.getElementById('sidebar-toggle');
                const overlay = document.getElementById('sidebar-overlay');

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    if (overlay) {
                        overlay.classList.toggle('hidden');
                    }
                }

                if (toggleButton) {
                    toggleButton.addEventListener('click', toggleSidebar);
                }

                if (overlay) {
                    overlay.addEventListener('click', toggleSidebar);
                }

                const sidebarLinks = document.querySelectorAll('#sidebar a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 768 && !sidebar.classList.contains('-translate-x-full')) {
                            toggleSidebar();
                        }
                    });
                });
            });
        </script>
        @endauth
    </body>
</html>
