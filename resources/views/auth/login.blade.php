@extends('layouts._blank')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/sneat/vendor/css/pages/page-auth.css') }}">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
        }

        .login-page__brand {
            flex: 1;
            display: none;
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, #1e3f73 0%, var(--primary-color) 45%, #4a7bc8 100%);
            color: #fff;
            padding: 3rem;
        }

        @media (min-width: 992px) {
            .login-page__brand {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }

        .login-page__brand::before,
        .login-page__brand::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .login-page__brand::before {
            width: 420px;
            height: 420px;
            top: -120px;
            right: -100px;
        }

        .login-page__brand::after {
            width: 280px;
            height: 280px;
            bottom: -80px;
            left: -60px;
        }

        .login-page__brand-content {
            position: relative;
            z-index: 1;
            max-width: 28rem;
        }

        .login-page__brand-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }

        .login-page__brand-title {
            font-family: 'mm-font-bold', serif;
            font-size: 2rem;
            line-height: 1.25;
            margin-bottom: 0.75rem;
        }

        .login-page__brand-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .login-page__features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .login-page__features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.875rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9375rem;
        }

        .login-page__features li i {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        .login-page__form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #f5f5f4;
        }

        .login-page .authentication-wrapper {
            min-height: auto;
            width: 100%;
            max-width: 26rem;
        }

        .login-page .authentication-inner {
            max-width: none;
        }

        .login-page .login-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(44, 90, 160, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .login-page .login-card .card-body {
            padding: 2rem 1.75rem;
        }

        @media (min-width: 576px) {
            .login-page .login-card .card-body {
                padding: 2.5rem 2.25rem;
            }
        }

        .login-page__mobile-brand {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        @media (min-width: 992px) {
            .login-page__mobile-brand {
                display: none;
            }
        }

        .login-page__mobile-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 0.875rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(44, 90, 160, 0.1);
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 0.875rem;
        }

        .login-page__heading {
            font-size: 1.375rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.375rem;
        }

        .login-page__subheading {
            color: #62748e;
            font-size: 0.9375rem;
            margin-bottom: 0;
        }

        .login-page .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.375rem;
        }

        .login-page .input-group-text {
            background-color: #fff;
            color: #94a3b8;
        }

        .login-page .input-group > .input-group-text:first-child:not(:last-child) {
            border-right: 0;
        }

        .login-page .input-group > .input-group-text:first-child + .form-control {
            border-left: 0;
            padding-left: 0;
        }

        .login-page .input-group:focus-within .form-control,
        .login-page .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .login-page .input-group:focus-within > .input-group-text:first-child {
            color: var(--primary-color);
        }

        .login-page .form-control.is-invalid {
            border-color: #dc3545;
        }

        .login-page .form-control.is-invalid + .input-group-text,
        .login-page .input-group:has(.is-invalid) .input-group-text {
            border-color: #dc3545;
        }

        .login-page .field-error {
            display: block;
            font-size: 0.8125rem;
            color: #dc3545;
            margin-top: 0.375rem;
        }

        .login-page .form-check-label {
            font-size: 0.875rem;
            color: #475569;
            user-select: none;
        }

        .login-page .btn-sign-in {
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .login-page .btn-sign-in:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.25);
        }

        .login-page .btn-sign-in:active {
            transform: translateY(0);
        }

        .login-page__footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8125rem;
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="login-page light-style">
        <aside class="login-page__brand" aria-hidden="true">
            <div class="login-page__brand-content">
                <div class="login-page__brand-icon">
                    <i class="bx bx-transfer-alt"></i>
                </div>
                <h1 class="login-page__brand-title">Exchange Ledger</h1>
                <p class="login-page__brand-text">
                    Streamline your money exchange operations with accurate records, balances, and daily reporting.
                </p>
                <ul class="login-page__features">
                    <li>
                        <i class="bx bx-check-circle"></i>
                        Track accounts and cash flows in one place
                    </li>
                    <li>
                        <i class="bx bx-check-circle"></i>
                        Secure access for your team members
                    </li>
                    <li>
                        <i class="bx bx-check-circle"></i>
                        Built for daily exchange desk workflows
                    </li>
                </ul>
            </div>
        </aside>

        <main class="login-page__form-panel">
            <div class="authentication-wrapper authentication-basic">
                <div class="authentication-inner">
                    <div class="card login-card">
                        <div class="card-body">
                            <div class="login-page__mobile-brand">
                                <div class="login-page__mobile-icon">
                                    <i class="bx bx-transfer-alt"></i>
                                </div>
                                <h4 class="brand-name mb-1"><b>Exchange Ledger</b></h4>
                                <p class="sub-title mb-0">Money Exchange Management</p>
                            </div>

                            <div class="mb-4 d-none d-lg-block">
                                <h2 class="login-page__heading">Welcome back</h2>
                                <p class="login-page__subheading">Sign in to continue to your dashboard</p>
                            </div>

                            <div class="mb-4 d-lg-none text-center">
                                <h2 class="login-page__heading">Sign in</h2>
                                <p class="login-page__subheading">Enter your credentials to continue</p>
                            </div>

                            <form id="formAuthentication" action="{{ route('login') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text"
                                            class="form-control @error('username') is-invalid @enderror"
                                            id="username"
                                            name="username"
                                            placeholder="Enter your username"
                                            value="{{ old('username') }}"
                                            autocomplete="username"
                                            autofocus
                                            required />
                                    </div>
                                    @error('username')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 form-password-toggle">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                            id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password"
                                            placeholder="Enter your password"
                                            autocomplete="current-password"
                                            aria-describedby="password"
                                            required />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    @error('password')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            name="remember"
                                            type="checkbox"
                                            id="remember-me"
                                            {{ old('remember') ? 'checked' : '' }} />
                                        <label class="form-check-label" for="remember-me">Remember me</label>
                                    </div>
                                </div>

                                <x-form.submit-btn :icon="false"
                                    formId="formAuthentication"
                                    class="w-100 btn-sign-in"
                                    label="Sign in" />

                                <p class="login-page__footer mb-0">
                                    &copy; {{ date('Y') }} Exchange Ledger
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
@endpush
