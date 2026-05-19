<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CPB-NGI Pawnshop') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('cpbngi_logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Figtree', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full p-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center border border-gray-100 dark:border-gray-700">
                
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('cpbngi_logo.png') }}" alt="CPB-NGI Logo" class="h-24 w-auto object-contain">
                </div>

                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                    {{ config('app.name', 'CPB-NGI Pawnshop') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm">
                    Pawnshop Management System
                </p>

                <div class="space-y-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-sm hover:shadow-md">
                                Login
                            </a>
                        @endauth
                    @endif
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'CPB-NGI Pawnshop') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
