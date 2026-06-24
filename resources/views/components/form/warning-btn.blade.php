@props([
    'route' => '',
    'label' => '',
    'class' => 'btn btn-sm text-white btn-success btn-confirm',
])
<a href="javascript:void(0)" class="{{ $class }}" data-url="{{ $route }}" title="{{ $label }}">
    {{ $label }}
</a>
