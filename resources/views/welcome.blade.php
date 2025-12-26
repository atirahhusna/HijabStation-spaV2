<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HijabStation Beauty</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-pink-50">

    <!-- Top Navigation -->
    <div class="flex justify-between items-center px-8 py-6">
        <div class="text-xl font-semibold text-pink-600">
            🌸 HijabStation Beauty
        </div>

        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-pink-600 font-semibold hover:underline">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="ml-4 px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <!-- Hero Section -->
    <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">

        <!-- Text -->
        <div>
            <h1 class="text-4xl font-bold text-gray-800 leading-tight">
                An AI Agent–Powered Intelligent Spa Management System for Skin Analysis and Personalized Skincare Recommendation <br>
                <span class="text-pink-500">Designed for Women</span>
            </h1>

            <p class="mt-6 text-gray-600 leading-relaxed">
                HijabStation Beauty is an AI-powered spa management system that provides
                intelligent skin analysis and personalized skincare recommendations,
                helping women make confident beauty decisions.
            </p>

            <div class="mt-8 flex gap-4">
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    Get Started
                </a>

                <a href="{{ route('login') }}"
                   class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-100 transition">
                    Book Treatment
                </a>
            </div>
        </div>

       
    </div>

    <!-- Features Section -->
    <div class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center">

            <div class="p-6 rounded-xl bg-pink-50">
                <h3 class="text-lg font-semibold text-pink-600">AI Skin Analysis</h3>
                <p class="mt-3 text-sm text-gray-600">
                    Upload your skin image and let our AI agent analyze skin conditions accurately.
                </p>
            </div>

            <div class="p-6 rounded-xl bg-pink-50">
                <h3 class="text-lg font-semibold text-pink-600">Smart Recommendations</h3>
                <p class="mt-3 text-sm text-gray-600">
                    Receive personalized skincare suggestions based on your skin type.
                </p>
            </div>

            <div class="p-6 rounded-xl bg-pink-50">
                <h3 class="text-lg font-semibold text-pink-600">Online Booking</h3>
                <p class="mt-3 text-sm text-gray-600">
                    Book spa treatments easily with real-time availability.
                </p>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-sm text-gray-500 py-6">
        © {{ date('Y') }} HijabStation Beauty. All rights reserved.
    </div>

</body>
</html>
