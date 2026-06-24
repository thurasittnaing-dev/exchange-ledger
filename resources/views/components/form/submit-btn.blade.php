@props([
    'label' => 'Submit',
    'formId' => '',
    'class' => '',
    'id' => 'submit-btn',
    'icon' => true,
])

<button type="button"
    id="{{ $id }}"
    class="btn btn-primary {{ $class }}"
    {{ $attributes }}
    onclick="actionFormSubmit(this, '#{{ $formId }}', event)">

    @if ($icon)
        <i class="ti ti-device-floppy"></i>
    @endif

    {{ $label }}
</button>
