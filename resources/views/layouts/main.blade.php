<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::user()->getAuthToken() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="app">
    </div>

    @routes

    <script>
        window.admin_panel = {
            user: {!! $user !!},
        };
    </script>

    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>
    <script src="{{ asset(mix('js/app-vendor.js')) }}" defer></script>
</body>

</html>