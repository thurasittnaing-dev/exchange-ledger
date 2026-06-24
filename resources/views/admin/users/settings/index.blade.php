@extends('layouts.app')

@section('title', 'Setting')
@push('styles')
@endpush

@section('content')
    <div class="container-fluid py-4 max-w-6xl">
        <div class="mb-4 pb-3 border-bottom">
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.5rem; tracking-tight">Settings</h2>
        </div>

        <div class="nav-align-top mb-4">
            @include('admin.users.settings.sidebar')

            <div class="tab-content">
                <div class="tab-pane fade show active">
                    @if(request()->routeIs('users.setting'))
                        @include('admin.users.settings.profile')
                    @elseif(request()->routeIs('users.passwordChange'))
                        @include('admin.users.settings.change-password')
                     @elseif(request()->routeIs('users.twoFactor'))
                        @include('admin.users.settings.2fa')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
