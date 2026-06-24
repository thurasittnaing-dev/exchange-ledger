@props([
    'id' => '',
    'name' => '',
    'label' => null,
    'options' => [],
    'selected' => null,
    'class' => null,
    'require' => false,
    'empty_label' => 'Choose',
    'showErrors' => true,
])

@php
    $errorKey = preg_replace(['/\[/', '/\]/'], ['.', ''], $name);
    $selectedValue = $showErrors ? old($errorKey, $selected) : $selected;
    $hasError = $showErrors && $errors->has($errorKey);
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label">
            {{ $label }}
            @if ($require)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select name="{{ $name }}" id="{{ $id ?? $name }}"
        class="form-control {{ $hasError ? 'is-invalid' : '' }} {{ $class }}">
        <option value="">{{ $empty_label }}</option>

        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ $selectedValue == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    @if ($hasError)
        <div class="invalid-feedback d-block">* {{ $errors->first($errorKey) }}</div>
    @endif
</div>
