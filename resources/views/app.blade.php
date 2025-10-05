<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Wikimedia Indonesia') }}</title>
        <meta name="author" content="Wikimedia Indonesia">
        <meta name="robots" content="index, follow">

        <!-- Favicon - Multiple formats for better browser support -->
        <link rel="icon" type="image/png" href="/Logo_WMID_2018_Menurun_HITAM.png">
        <link rel="apple-touch-icon" href="/Logo_WMID_2018_Menurun_HITAM.png">
        
        <!-- Meta theme color -->
        <meta name="theme-color" content="#54b0af">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>