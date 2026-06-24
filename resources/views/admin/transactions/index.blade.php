@extends('layouts.app')

@section('title', 'Transactions')
@push('styles')
    <style>
        .stat-card {
            border: none;
            border-radius: 0.875rem;
            overflow: hidden;
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-emoney {
            background: linear-gradient(135deg, #7367f0 0%, #5e50ee 100%);
            color: #fff;
        }

        .stat-cash {
            background: linear-gradient(135deg, #28c76f 0%, #1f9d57 100%);
            color: #fff;
        }

        .stat-profit-cash {
            background: #fff;
            border: 1px solid #28c76f33;
        }

        .stat-profit-cash .stat-value {
            color: #28c76f;
        }

        .stat-profit-emoney {
            background: #fff;
            border: 1px solid #7367f033;
        }

        .stat-profit-emoney .stat-value {
            color: #7367f0;
        }

        .stat-profit-total {
            background: #fff;
            border: 1px solid #ff9f4333;
        }

        .stat-profit-total .stat-value {
            color: #ff9f43;
        }

        .profit-filter-note {
            font-size: 0.8rem;
            color: #6c757d;
        }

        #transactions-table_wrapper .dataTables_scrollBody,
        #transactions-table_wrapper .dataTables_scroll {
            overflow: visible !important;
            height: auto !important;
            max-height: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card stat-card stat-emoney shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="stat-label mb-1">လက်ရှိ EMoney Amount</div>
                        <div class="stat-value">{{ number_format($emoneyTotal) }} <small class="fs-6">MMK</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card stat-cash shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="stat-label mb-1">လက်ရှိ Cash Amount</div>
                        <div class="stat-value">{{ number_format($cashTotal) }} <small class="fs-6">MMK</small></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <h6 class="mb-0">Service Fee Profit</h6>
                <div class="profit-filter-note">Filter result အလိုက် profit ပြသပါသည်</div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card stat-profit-cash shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">Cash Profit</div>
                        <div class="stat-value" id="profit-cash">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card stat-profit-emoney shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">EMoney Profit</div>
                        <div class="stat-value" id="profit-emoney">0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card stat-profit-total shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">Total Profit</div>
                        <div class="stat-value" id="profit-total">0</div>
                    </div>
                </div>
            </div>
        </div>

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Cash-In / Cash-Out</div>
                <div class="page-sub-header">ငွေလွှဲ / ငွေထုတ် Transactions</div>
            </x-slot:title>

            <x-slot:actions>
                @can('has-permission', 'transaction-create')
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                        <i class="ti ti-arrows-exchange"></i> New Transaction
                    </a>
                @endcan
                @can('has-permission', 'transaction-export')
                    <button id="export-xlsx" class="btn btn-success">
                        <i class="ti ti-file-type-xls"></i> Export
                    </button>
                @endcan
            </x-slot:actions>
        </x-datatable.header>

        <x-datatable.filter title='Transaction Filter'>
            @include('admin.transactions.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="transactions-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Type</th>
                        <th>Fee Type</th>
                        <th>Amount</th>
                        <th>Fee</th>
                        <th>Cash Impact</th>
                        <th>EMoney Impact</th>
                        <th>Cash Profit</th>
                        <th>EMoney Profit</th>
                    </tr>
                </thead>
            </table>
        </x-datatable.wrapper>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.transaction-data-config')
@endpush
