@extends('layouts.app')

@section('title', 'Cash Opening / Reset')
@push('styles')
    <style>
        .cash-form-balance {
            background: #f8f9fa;
            border: 1px dashed #28c76f;
            border-radius: 0.75rem;
        }

        .cash-form-balance .amount {
            color: #28c76f;
            font-size: 1.75rem;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="cash-form-balance p-4 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="text-muted mb-1">လက်ရှိ Cash Balance</div>
                <div class="amount">{{ number_format($currentBalance) }} <small class="text-muted fs-6">MMK</small></div>
            </div>
            {{-- <div class="text-muted small">
                Opening သည် လက်ရှိ balance ထဲ amount ထပ်ပေါင်းမည်။ Reset သည် balance ကို amount သို့ ပြန်ညှိမည်။
            </div> --}}
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Cash Opening / Reset</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="cash-money-form">
                @csrf

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <x-form.select name="reference_type" id="reference_type" label="Type" :options="$referenceTypes"
                                class="lib-s2" :require="true" empty_label="Choose Type" />
                        </div>

                        <div class="col-md-6">
                            <x-form.input name="amount" id="amount" label="Amount" placeholder="Enter Amount"
                                class="num-only" :require="true" />
                            <small id="amount-hint" class="text-muted">Select type first.</small>
                        </div>

                        <div class="col-md-12">
                            <x-form.input name="description" label="Description"
                                placeholder="Enter Description (Optional)" />
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <a href="{{ route('cash_money.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <x-form.submit-btn :icon="false" formId="cash-money-form" label="Save" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const opening = @json(\App\Enums\CashHistoryReferenceType::OPENING->value);
            const reset = @json(\App\Enums\CashHistoryReferenceType::RESET->value);

            function updateAmountHint() {
                const type = $('select[name="reference_type"]').val();
                const $label = $('label[for="amount"]');
                const $hint = $('#amount-hint');

                if (type === reset) {
                    $label.text('New Balance *');
                    $hint.text('Cash balance will be reset to this amount.');
                } else if (type === opening) {
                    $label.text('Opening Amount *');
                    $hint.text('This amount will be added to the current cash balance.');
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
