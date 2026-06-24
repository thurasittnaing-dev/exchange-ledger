@can('has-permission', 'work-plan-list')
    <li class="menu-item {{ navGroupActive(['work_plans']) }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon ti ti-clipboard-list"></i>
            <div data-i18n="Layouts">Work Plan</div>
        </a>

        <ul class="menu-sub">
            @can('has-permission', 'work-plan-list')
                @php $defaultSelected = ['type' => 'submitted','task' => 'all-tasks'] @endphp
                <li class="menu-item {{ navLinkActive(['work_plans.index', 'work_plans.show']) }}">
                    <a href="{{ route('work_plans.index', $defaultSelected) }}" class="menu-link ">
                        <div data-i18n="Without menu">
                            စာရင်းများ
                            <span class="badge badge-center rounded-pill bg-primary ms-1">{{ workPlanCounts() }}</span>
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan


@can('has-permission', 'work-done-list')
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon ti ti-checkup-list"></i>
            <div data-i18n="Layouts">Work Done</div>
        </a>

        <ul class="menu-sub">
            @can('has-permission', 'work-plan-list')
                <li class="menu-item ">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <div data-i18n="Without menu">
                            စာရင်းများ
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
