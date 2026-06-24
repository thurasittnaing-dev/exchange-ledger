@can('has-permission', ['user-list', 'permission-list', 'bank-type-list', 'account-list', 'account-balance-list', 'cash-money-list'])

    <li
        class="menu-item {{ navGroupActive(['users', 'permissions', 'bank_types', 'accounts', 'account_balance_histories', 'cash_money']) }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon ti ti-settings"></i>
            <div data-i18n="Layouts">စီမံခန့်ခွဲမှု</div>
        </a>

        <ul class="menu-sub">

            @can('has-permission', 'account-balance-list')
                <li
                    class="menu-item {{ navLinkActive(['account_balance_histories.index', 'account_balance_histories.create']) }}">
                    <a href="{{ route('account_balance_histories.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            Account Balance
                        </div>
                    </a>
                </li>
            @endcan

            @can('has-permission', 'cash-money-list')
                <li class="menu-item {{ navLinkActive(['cash_money.index', 'cash_money.create']) }}">
                    <a href="{{ route('cash_money.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            Cash Money
                        </div>
                    </a>
                </li>
            @endcan

            @can('has-permission', 'bank-type-list')
                <li class="menu-item {{ navLinkActive(['bank_types.index', 'bank_types.edit', 'bank_types.create']) }} ">
                    <a href="{{ route('bank_types.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            ဘဏ်အမျိုးအစားများ
                        </div>
                    </a>
                </li>
            @endcan

            @can('has-permission', 'account-list')
                <li class="menu-item {{ navLinkActive(['accounts.index', 'accounts.edit', 'accounts.create']) }}">
                    <a href="{{ route('accounts.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            အကောင့်များ
                        </div>
                    </a>
                </li>
            @endcan



            @can('has-permission', 'user-list')
                <li class="menu-item {{ navLinkActive(['users.index', 'users.edit', 'users.create']) }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            အသုံးပြုသူစီမံခန့်ခွဲမှု
                            <span class="badge badge-center rounded-pill bg-primary ms-1">{{ usersCount() }}</span>
                        </div>
                    </a>
                </li>
            @endcan

            @can('has-permission', 'permission-list')
                <li class="menu-item {{ navLinkActive(['permissions.index', 'permissions.edit', 'permissions.create']) }} ">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            လုပ်ပိုင်ခွင့်စီမံခန့်ခွဲမှု
                        </div>
                    </a>
                </li>
            @endcan




        </ul>
    </li>
@endcan
