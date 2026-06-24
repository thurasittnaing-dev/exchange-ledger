@extends('layouts.app')

@section('title', 'Bank Type Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ $bankType ? 'Bank Type Edit' : 'Bank Type Create' }}</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="bank-type-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <x-form.input name="name" label="Bank Type Name" placeholder="Enter Bank Type Name" :require="true"
                                :value="$bankType?->name" />
                        </div>

                        <div class="col-md-6">
                            <x-form.select name="provider" label="Provider" :options="$providers" class="lib-s2"
                                :require="true" :selected="$bankType?->provider" empty_label="Choose Provider" />
                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('bank_types.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="bank-type-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
