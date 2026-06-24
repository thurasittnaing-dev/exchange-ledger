<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="{{ route('users.setting') }}"
            class="nav-link {{ request()->routeIs('users.setting') ? 'active text-primary' : 'text-secondary' }}">
            <span class="text-dark">Profile</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('users.passwordChange') }}"
            class="nav-link {{ request()->routeIs('users.passwordChange') ? 'active text-primary' : 'text-secondary' }}">
            <span class="text-dark"> Change Password</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('users.twoFactor') }}"
            class="nav-link {{ request()->routeIs('users.twoFactor') ? 'active text-primary' : 'text-secondary' }}">
            <span class="text-dark">Two-Factor Auth</span>
        </a>
    </li>
</ul>
