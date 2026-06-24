@php
    $user = auth()->user();
@endphp

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div>
            <div class="welcome-text">Welcome back, {{ $user?->full_name }}</div>
            <div class="welcome-sub-text">{{ date('l, F j, Y') }}</div>
        </div>
        <!-- /Search -->


        <ul class="navbar-nav flex-row align-items-center ms-auto">

            @if ($user->isDeveloper() || session()->has('impersonator'))
                <li class="nav-item lh-1 me-3 mt-3">
                    @include('layouts.master')
                </li>
            @endif

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">

                        @if ($user->profile_image)
                            <img src="{{ route('users.showProfile', $user) }}" alt class="w-px-40 h-auto rounded-circle"
                                loading="lazy" />
                        @else
                            <img src="{{ asset('assets/images/avater.jpg') }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="z-index: 2;">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">

                                        @if ($user->profile_image)
                                            <img src="{{ route('users.showProfile', $user) }}" alt
                                                class="w-px-40 h-auto rounded-circle" loading="lazy" />
                                        @else
                                            <img src="{{ asset('assets/images/avater.jpg') }}" alt
                                                class="w-px-40 h-auto rounded-circle" loading="lazy" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block mt-2"
                                        style="font-size: 0.8rem;">{{ $user?->name }}</span>
                                    <small
                                        class="text-muted">{{ \App\Enums\Roles::tryFrom($user?->role)?->label() }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    @if ($user->isDeveloper())
                        <li>
                            <a class="dropdown-item" target="_blank" href="{{ url('/sl/pni/logs') }}">
                                <i class="ti ti-database me-2"></i>
                                <span class="align-middle">View Logs</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a class="dropdown-item" href="{{ route('users.passwordChange') }}">
                            <i class="ti ti-lock me-2"></i>
                            <span class="align-middle">Change Pasword</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        @include('auth.logout')
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
