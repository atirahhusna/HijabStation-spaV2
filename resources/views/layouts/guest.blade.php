<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HijabStation') }}</title>

    <!-- Figtree font (from Laravel Breeze) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- Global font and background -->
<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-pink-50 via-white to-pink-100">
    <div class="min-h-screen flex flex-col items-center">
<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-white via-pink-50 to-white min-h-screen flex flex-col items-center justify-center">

    <!-- HijabStation Logo -->
    <div class="mt-12">
        <a href="/" class="group">
            <img
                src="{{ asset('images/hijabstation-logo.png') }}"
                alt="HijabStation Logo"
                class="w-36 h-36 rounded-full shadow-lg ring-4 ring-white group-hover:ring-pink-200 transition duration-300 transform group-hover:scale-105"
            >
        </a>
    </div>

    <!-- Auth Card -->
    <div class="w-full sm:max-w-md mt-8 px-10 py-8 bg-white/90 backdrop-blur-md border border-pink-100 shadow-xl sm:rounded-3xl ring-1 ring-pink-200 transition duration-300">
        <h1 class="text-center text-2xl font-semibold text-pink-700 mb-4">Welcome to HijabStation</h1>
        <p class="text-center text-sm text-gray-600 mb-6">Find your elegance. Register or log in to continue.</p>
        {{ $slot }}
    </div>

    <!-- Footer -->
        <footer class="mt-auto w-full py-6 flex flex-col items-center text-sm text-pink-600">
            <p>&copy; {{ date('Y') }} <span class="font-medium">HijabStation</span>. All rights reserved.</p>
            <p class="mt-1">
                <a href="#" class="hover:underline">Privacy Policy</a>
                <span class="mx-2">•</span>
                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
            </p>
        </footer>
    </div>
</body>
</html>