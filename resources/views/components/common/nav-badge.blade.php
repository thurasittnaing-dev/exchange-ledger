@props([
    'count' => 0,
])

@if ($count > 0)
    <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">{{ $count }}</span>
@endif
