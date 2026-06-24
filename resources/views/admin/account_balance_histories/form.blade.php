@extends('layouts.app')

@section('title', 'Add / Reset Balance')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Add / Reset Account Balance</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="account-balance-form">
                @csrf

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <x-form.select name="account_id" label="Account" :options="$accounts" class="lib-s2"
                                :require="true" empty_label="Choose Account" />
                        </div>

                        <div class="col-md-6">
                            <x-form.select name="reference_type" id="reference_type" label="Type" :options="$referenceTypes"
                                class="lib-s2" :require="true" empty_label="Choose Type" />
                        </div>

                        <div class="col-md-6">
                            <x-form.input name="amount" id="amount" label="Amount" placeholder="Enter Amount"
                                class="num-only" :require="true" />
                            <small id="amount-hint" class="text-muted">Add amount will be added to current balance.</small>
                        </div>

                        <div class="col-md-12">
                            <x-form.input name="description" label="Description" placeholder="Enter Description (Optional)" />
                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('account_balance_histories.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="account-balance-form" label="Save" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const manualAdd = @json(\App\Enums\AccountHistoryReferenceType::MANUAL_ADD->value);
            const manualReset = @json(\App\Enums\AccountHistoryReferenceType::MANUAL_RESET->value);

            function updateAmountHint() {
                const type = $('select[name="reference_type"]').val();
                const $label = $('label[for="amount"]');
                const $hint = $('#amount-hint');

                if (type === manualReset) {
                    $label.text('New Balance *');
                    $hint.text('Account balance will be reset to this amount.');
                } else if (type === manualAdd) {
                    $label.text('Add Amount *');
                    $hint.text('This amount will be added to the current balance.');
                } else {
                    $label.text('Amount *');
                    $hint.text('Select type first.');
                }
            }

            $('select[name="reference_type"]').on('change select2:select', updateAmountHint);
            updateAmountHint();
        });
    </script>
@endpush
