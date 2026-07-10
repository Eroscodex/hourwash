<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WashHub Laundry') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('hourwash.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-cyan-500 to-sky-400 px-6">


    <!-- Background bubbles -->
    <div class="absolute top-10 left-10 w-40 h-40 bg-white/20 rounded-full"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 bg-white/20 rounded-full"></div>


    <div class="relative w-full max-w-md">


        <!-- Logo -->
        <div class="p-6 flex flex-col items-center text-center">

            <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center overflow-hidden">
                <img
                    src="{{ asset('hourwash.ico') }}"
                    alt="Hour Wash Logo"
                    class="w-16 h-16 object-contain"
                >
            </div>

            <h1 class="mt-4 text-2xl font-bold">
                Hour Wash
            </h1>

            <p class="text-blue-100 text-sm">
                Laundry Management
            </p>

        </div>



        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-2xl px-8 py-8">

            {{ $slot }}

        </div>


        <!-- Footer -->

        <p class="text-center text-white text-sm mt-6">
            © {{ date('Y') }} Hour Wash Laundry. All rights reserved.
        </p>


    </div>


</div>


</body>
</html>