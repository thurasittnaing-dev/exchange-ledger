<ul class="menu-inner py-1">

    <li class="menu-header small text-uppercase"><span class="menu-header-text">Menus</span></li>

    <!-- Dashboard -->
    <li class="menu-item {{ navLinkActive(['dashboard']) }}">
        <a href="{{ route('dashboard') }}" class="menu-link">
            <i class="menu-icon ti ti-layout-dashboard"></i>
            <div data-i18n="Analytics">ပင်မစာမျက်နှာ</div>
        </a>
    </li>

    <!-- Transactions -->
    @include('layouts.menus.transactions-sidebar')

    <!-- Management Menu -->
    @include('layouts.menus.management-sidebar')
</ul>
