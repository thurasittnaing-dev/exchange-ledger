<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Exchange Ledger | @yield('title')</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    @include('layouts.styles')

    @stack('styles')
</head>

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('dashboard') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="app-brand-mark">
                                <i class="bx bx-transfer-alt"></i>
                            </span>
                        </span>
                        <span class="app-brand-text demo">
                            <span class="main-text">Exchange Ledger</span>
                            <span class="sub-text">Money Exchange Management</span>
                        </span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                @include('layouts.sidebar')
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                @include('layouts.navbar')
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <form id="global-delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form id="global-update-form" method="POST" style="display: none;">
            @csrf
            @method('POST')
        </form>
    </div>
    <!-- / Layout wrapper -->

    @stack('modals')

    @include('layouts.scripts')
    @stack('scripts')
</body>

</html>
