@extends('layouts.app')

@section('title', 'Permission')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Permission @if ($method == 'PUT')
                        Update
                    @else
                        Create
                    @endif
                </h5>
            </div>

            <form method="POST" action="{{ $route }}" id="user-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Module --}}
                        <div class="col-md-4">
                            <x-form.input name="module" label="Module" placeholder="Enter Module Name" :require="true"
                                :value="$permission?->module" />
                        </div>

                        {{-- Code --}}
                        <div class="col-md-4">
                            <x-form.input name="code" label="Code" placeholder="Enter Code" :require="true"
                                :value="$permission?->code" />
                        </div>

                        {{-- Name --}}
                        <div class="col-md-4">
                            <x-form.input name="name" label="Name" placeholder="Enter Name" :require="true"
                                :value="$permission?->name" />
                        </div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="user-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
