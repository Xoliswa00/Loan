<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Liger Management') }}</title>

    <!-- Icons and Fonts -->
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">


    <!-- App Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mobile UI enhancements */
        @media (max-width: 640px) {
            header, footer, main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            main > * {
                font-size: 0.875rem;
            }
            input, select, textarea {
                font-size: 0.875rem !important;
                padding: 0.5rem 0.75rem !important;
            }
            h2, h3, h4 {
                font-size: 1.125rem;
            }
        }
    </style>
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen flex flex-col bg-gray-100 relative">
        <!-- Navigation -->
        @include('layouts.navigation')


        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1 bg-white px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 text-center text-sm text-gray-600 dark:text-gray-300 py-6 mt-auto border-t border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-4">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div>
                        &copy; {{ date('Y') }} {{ config('app.name', 'Liger Management') }}. All rights reserved.
                    </div>
                    <div class="mt-2 sm:mt-0 space-x-4">
                        <a href="/about" class="hover:text-indigo-600 transition">About</a>
                        <a href="/contact" class="hover:text-indigo-600 transition">Contact</a>
                        <a href="/privacy" class="hover:text-indigo-600 transition">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- WhatsApp Chat Button -->
        <a href="https://wa.me/27600000000" target="_blank" class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-full shadow-lg flex items-center space-x-2">
            <i class="fab fa-whatsapp text-xl"></i>
            <span class="hidden sm:inline">Chat</span>
        </a>

        <!-- Scroll to Top Button -->
        <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="fixed bottom-20 right-6 z-40 bg-gray-800 hover:bg-gray-900 text-white p-2 rounded-full shadow-md">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
</body>
</html>
