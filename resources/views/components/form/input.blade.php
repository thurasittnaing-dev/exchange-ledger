@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'class' => '',
    'require' => false,
    'showErrors' => true,
])

@php
    $errorKey = preg_replace(['/\[/', '/\]/'], ['.', ''], $name);
    $inputValue = $showErrors ? old($errorKey, $value ?? request($name)) : ($value ?? request($name));
    $hasError = $showErrors && $errors->has($errorKey);
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label">
            {{ $label }}
            @if ($require)
                <span class="text-danger">*</span>
            @else
                <small class="text-muted">(Optional)</small>
            @endif
        </label>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        value="{{ $inputValue }}" placeholder="{{ $placeholder }}"
        class="form-control {{ $hasError ? 'is-invalid' : '' }} {{ $class }}">

    @if ($hasError)
        <div class="invalid-feedback d-block">* {{ $errors->first($errorKey) }}</div>
    @endif
</div>
