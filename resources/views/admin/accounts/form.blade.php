@extends('layouts.app')

@section('title', 'Account Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ $account ? 'Account Edit' : 'Account Create' }}</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="account-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <x-form.input name="name" label="Account Name" placeholder="Enter Account Name"
                                :require="true" :value="$account?->name" />
                        </div>

                        <div class="col-md-6">
                            <x-form.select name="bank_type_id" label="Bank Type" :options="$bankTypes" class="lib-s2"
                                :require="true" :selected="$account?->bank_type_id" empty_label="Choose Bank Type" />
                        </div>

                        {{-- <div class="col-md-6">
                            <x-form.input name="balance" label="Balance" placeholder="Enter Balance" class="num-only"
                                :require="$account !== null" :value="$account?->balance ?? 0" />
                        </div> --}}

                    </div>

                </div>

                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="account-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
