@props([
    'types' => [],
    'currentType' => 'submitted',
    'module' => 'work_plans',
    'workPlan' => null,
    'planCounts' => [],
])

<div class="nav-align-top mb-4">
    <ul class="nav nav-tabs">
        @foreach ($types as $value => $type)
            <li class="nav-item">
                <a href="{{ route('work_plans.index', ['type' => $value]) }}"
                    class="nav-link {{ $currentType == $value ? 'active' : '' }}">
                    <span class="text-dark">{{ $type }}</span>
                    <x-common.nav-badge count="{{ $planCounts[$value]['all'] }}" />
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active">
            @include("admin.$module.data." . $currentType, [$workPlan, $planCounts, $currentType])
        </div>
    </div>
</div>
