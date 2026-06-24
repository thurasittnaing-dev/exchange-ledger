{{--  Popper and Bootstrap --}}
<script src="{{ asset('assets/sneat/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/sneat/vendor/js/bootstrap.js') }}"></script>

<script src="{{ asset('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/sneat/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/sneat/js/main.js') }}"></script>


<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        // Success Alerts
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // Error Alerts
        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Laravel Validation Errors (Global)
        @if ($errors->any())
            @php
                $validationTitle = old('_modal_id')
                    ? 'Please correct the highlighted fields in the form.'
                    : $errors->first();
            @endphp
            Toast.fire({
                icon: 'error',
                title: @json($validationTitle)
            });
        @endif

        // Fortify  Status Messages
        @if (session('status'))
            Toast.fire({
                icon: 'info',
                title: "{{ session('status') }}"
            });
        @endif
    });
</script>
