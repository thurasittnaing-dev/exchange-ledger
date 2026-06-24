<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/mohl-logo.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Exchange Ledger | @yield('title')</title>

    @include('layouts.styles')

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    @yield('content')

    @include('layouts.scripts')
    @stack('scripts')
</body>

</html>
