@extends('layouts._blank')

@section('title', '404')
@push('styles')
@endpush

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            <!-- Error Code -->
            <h1 class="display-1 fw-bold text-primary">429</h1>

            <!-- Error Message -->
            <h2 class="h4 fw-semibold mb-3">Too Many Requests</h2>

            <!-- Helpful Description -->
            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                You (or a script you're running) are hitting the server too hard and have been rate-limited.
            </p>

            <!-- Dynamic Illustration / Icon -->
            <div class="mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor"
                    class="text-light-emphasis bi bi-exclamation-triangle" viewBox="0 0 16 16">
                    <path
                        d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                    <path
                        d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                </svg>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-4 gap-3 shadow-sm">
                    <i class="ti ti-pointer-2"></i>Back to Home
                </a>
                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-lg px-4">
                    Go Back
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module"></script>
@endpush
