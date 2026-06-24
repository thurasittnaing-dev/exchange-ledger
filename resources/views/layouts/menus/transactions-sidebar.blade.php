@can('has-permission', 'transaction-list')
    <li class="menu-item {{ navLinkActive(['transactions.index', 'transactions.create']) }}">
        <a href="{{ route('transactions.index') }}" class="menu-link">
            <i class="menu-icon ti ti-arrows-exchange"></i>
            <div data-i18n="Transactions">ငွေလွှဲ / ငွေထုတ်</div>
        </a>
    </li>
@endcan
