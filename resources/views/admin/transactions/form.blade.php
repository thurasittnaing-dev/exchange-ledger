@extends('layouts.app')

@section('title', 'New Transaction')
@push('styles')
    <style>
        .balance-strip {
            background: #f8f9fa;
            border-radius: 0.75rem;
            border: 1px solid #ebebeb;
        }

        .balance-strip .item {
            padding: 1rem 1.25rem;
        }

        .balance-strip .label {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .balance-strip .value {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .balance-strip .emoney {
            color: #7367f0;
        }

        .balance-strip .cash {
            color: #28c76f;
        }

        .preview-card {
            background: #fcfcfd;
            border: 1px dashed #d8d6de;
            border-radius: 0.75rem;
        }

        .preview-card .preview-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            padding: 0.35rem 0;
            border-bottom: 1px solid #f1f1f2;
        }

        .preview-row:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="balance-strip d-flex flex-wrap mb-4">
            <div class="item flex-fill">
                <div class="label">EMoney Amount</div>
                <div class="value emoney">{{ number_format($emoneyTotal) }} MMK</div>
            </div>
            <div class="item flex-fill border-start">
                <div class="label">Cash Amount</div>
                <div class="value cash">{{ number_format($cashTotal) }} MMK</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">New Cash-In / Cash-Out</h5>
                    </div>

                    <form method="POST" action="{{ $route }}" id="transaction-form">
                        @csrf

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form.select name="type" label="Transaction Type" :options="$types" class="lib-s2"
                                        :require="true" empty_label="Choose Type" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select name="fee_type" label="Service Fee Type" :options="$feeTypes"
                                        class="lib-s2" :require="true" empty_label="Choose Fee Type" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.select name="account_id" label="Account" :options="$accounts" class="lib-s2"
                                        :require="true" empty_label="Choose Account" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="amount" id="amount" label="Amount" placeholder="Enter Amount"
                                        class="num-only" :require="true" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="fee_amount" id="fee_amount" label="Service Fee" placeholder="Enter Fee"
                                        class="num-only" :require="true" :value="0" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.input name="description" label="Description"
                                        placeholder="Enter Description (Optional)" />
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-end gap-2">
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <x-form.submit-btn :icon="false" formId="transaction-form" label="Save Transaction" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="preview-card p-4 h-100">
                    <div class="preview-title">Impact Preview</div>
                    <div class="preview-row"><span>Cash Impact</span><strong id="preview-cash">0</strong></div>
                    <div class="preview-row"><span>EMoney Impact</span><strong id="preview-emoney">0</strong></div>
                    <div class="preview-row"><span>Cash Profit</span><strong id="preview-cash-profit">0</strong></div>
                    <div class="preview-row"><span>EMoney Profit</span><strong id="preview-emoney-profit">0</strong></div>
                    <hr>
                    <div class="small text-muted" id="preview-note">Type, fee type, amount ဖြည့်ပြီး preview ကြည့်ပါ။</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const CASH_IN = @json(\App\Enums\TransactionType::CASH_IN->value);
            const CASH_OUT = @json(\App\Enums\TransactionType::CASH_OUT->value);
            const EXACT = @json(\App\Enums\FeeType::EXACT->value);
            const NET = @json(\App\Enums\FeeType::NET->value);

            function fmt(n) {
                return Number(n || 0).toLocaleString();
            }

            function compute() {
                const type = $('select[name="type"]').val();
                const feeType = $('select[name="fee_type"]').val();
                const amount = parseInt($('#amount').val() || '0', 10);
                const fee = parseInt($('#fee_amount').val() || '0', 10);

                if (!type || !feeType || amount <= 0) {
                    $('#preview-note').text('Type, fee type, amount ဖြည့်ပြီး preview ကြည့်ပါ။');
                    return;
                }

                if (feeType === NET && fee >= amount) {
                    $('#preview-note').text('Net fee type မှာ fee က amount ထက် နည်းရပါမည်။');
                    return;
                }

                let cash = 0, emoney = 0, cashProfit = 0, emoneyProfit = 0, note = '';

                if (type === CASH_IN && feeType === EXACT) {
                    cash = amount + fee;
                    emoney = amount;
                    cashProfit = fee;
                    note = 'Cash-In Exact: Cash +' + fmt(cash) + ', EMoney -' + fmt(emoney);
                } else if (type === CASH_IN && feeType === NET) {
                    cash = amount;
                    emoney = amount - fee;
                    emoneyProfit = fee;
                    note = 'Cash-In Net: Cash +' + fmt(cash) + ', EMoney -' + fmt(emoney);
                } else if (type === CASH_OUT && feeType === EXACT) {
                    cash = amount;
                    emoney = amount + fee;
                    emoneyProfit = fee;
                    note = 'Cash-Out Exact: Cash -' + fmt(cash) + ', EMoney +' + fmt(emoney);
                } else if (type === CASH_OUT && feeType === NET) {
                    cash = amount - fee;
                    emoney = amount;
                    cashProfit = fee;
                    note = 'Cash-Out Net: Cash -' + fmt(cash) + ', EMoney +' + fmt(emoney);
                }

                $('#preview-cash').text(fmt(cash));
                $('#preview-emoney').text(fmt(emoney));
                $('#preview-cash-profit').text(fmt(cashProfit));
                $('#preview-emoney-profit').text(fmt(emoneyProfit));
                $('#preview-note').text(note);
            }

            $('#amount, #fee_amount').on('input', compute);
            $('select[name="type"], select[name="fee_type"]').on('change select2:select', compute);
            compute();
        });
    </script>
@endpush
