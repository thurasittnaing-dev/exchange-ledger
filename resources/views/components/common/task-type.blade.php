@props([
    'task' => request('task', 'my-tasks'),
    'planCounts' => [],
    'value' => '',
])

<div class="my-3">
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input" type="radio" name="task" id="my-tasks" value="my-tasks"
            {{ $task == 'my-tasks' ? 'checked' : '' }}
            onchange="let url = new window.URL(window.location.href); url.searchParams.set('task', this.value); window.location.href = url.href;" />
        <label class="form-check-label" for="my-tasks">
            My Tasks <x-common.nav-badge count="{{ $planCounts[$value]['my'] }}" />
        </label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="task" id="all-tasks" value="all-tasks"
            {{ $task == 'all-tasks' ? 'checked' : '' }}
            onchange="let url = new window.URL(window.location.href); url.searchParams.set('task', this.value); window.location.href = url.href;" />
        <label class="form-check-label" for="all-tasks">
            All
            <x-common.nav-badge count="{{ $planCounts[$value]['all'] }}" />
        </label>
    </div>
</div>
