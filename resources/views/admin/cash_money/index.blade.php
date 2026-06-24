@extends('layouts.app')

@section('title', 'Cash Money')
@push('styles')
    <style>
        .cash-balance-card {
            background: linear-gradient(135deg, #28c76f 0%, #1f9d57 100%);
            border: none;
            color: #fff;
        }

        .cash-balance-card .balance-label {
            opacity: 0.9;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
        }

        .cash-balance-card .balance-amount {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .cash-balance-card .balance-unit {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.95;
        }

        .cash-icon-wrap {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card cash-balance-card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="cash-icon-wrap">
                            <i class="ti ti-cash ti-xl"></i>
                        </div>
                        <div>
                            <div class="balance-label mb-1">လက်ရှိ Cash Balance</div>
                            <div class="balance-amount">
                                {{ number_format($currentBalance) }}
                                <span class="balance-unit">MMK</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @can('has-permission', 'cash-money-create')
                            <a href="{{ route('cash_money.create') }}" class="btn btn-light text-success fw-semibold">
                                <i class="ti ti-plus"></i> Opening / Reset
                            </a>
                        @endcan
                        @can('has-permission', 'cash-money-export')
                            <button id="export-xlsx" class="btn btn-outline-light">
                                <i class="ti ti-file-type-xls"></i> Export
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Cash Money History</div>
                <div class="page-sub-header">Opening & Reset Records</div>
            </x-slot:title>
        </x-datatable.header>

        <x-datatable.filter title='Cash History Filter'>
            @include('admin.cash_money.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="cash-money-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Action</th>
                        <th>Amount</th>
                        <th>Previous Balance</th>
                        <th>Description</th>
                    </tr>
                </thead>
            </table>
        </x-datatable.wrapper>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.cash-money-data-config')
@endpush
