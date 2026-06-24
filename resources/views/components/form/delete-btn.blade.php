@props([
    'route' => '',
    'label' => '',
    'class' => 'btn-link text-danger  d-inline',
])

<a href="javascript:void(0)" class="{{ $class }} btn-delete" data-url="{{ $route }}" title="delete">
    <i class="ti ti-trash icon-font"></i> {{ $label }}
</a>
