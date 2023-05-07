<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->

    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div id="app"></div>


    @routes
    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>
    <script src="{{ asset(mix('js/app-vendor.js')) }}" defer></script>
</body>

</html>