@props([
    'id' => '',
    'targetId' => '',
    'class' => '',
])

<button type="submit" data-bs-toggle="collapse" href="#{{ $targetId }}" role="button"
    class="btn {{ $class }} work-plan-action-btn" aria-expanded="false" aria-controls="collapseExample"
    id="{{ $id }}">
    {{ $slot }}
</button>
