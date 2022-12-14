<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="robots" content="none" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'SpeedApp') }}</title>

        <link rel="icon" href="{{ url('favicon.ico') }}">
    
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vue.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        @inertia

        @env ('local')
            <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/metrics.js"></script>
            <!-- <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script> -->
        @endenv
    </body>
</html>
