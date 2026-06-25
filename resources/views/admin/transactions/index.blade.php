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

        .tx-table-card {
            border: none;
            border-radius: 0.875rem;
            box-shadow: 0 2px 12px rgba(47, 43, 61, 0.06);
        }

        .tx-table-card .table-toolbar {
            border-bottom: 1px solid #eee;
            padding: 1rem 1.25rem;
        }

        .tx-table-card .record-badge {
            font-size: 0.8rem;
            font-weight: 600;
        }

        #transactions-table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
            background: #f8f9fa !important;
        }

        #transactions-table tbody td {
            font-size: 0.9rem;
            vertical-align: middle;
            white-space: nowrap;
        }

        #transactions-table tfoot th {
            background: #eef2ff;
            color: #4338ca;
            font-weight: 700;
            font-size: 0.9rem;
            border-top: 2px solid #7367f0;
            white-space: nowrap;
        }

        #transactions-table tfoot .total-label {
            color: #4338ca;
            font-size: 0.95rem;
        }

        .tx-table-card .table-responsive {
            padding: 0 1rem 1rem;
        }

        #transactions-table_wrapper .dataTables_length,
        #transactions-table_wrapper .dataTables_info {
            padding-left: 1rem;
            font-size: 0.875rem;
        }

        #transactions-table_wrapper .dataTables_paginate {
            padding-right: 1rem;
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

        <div class="card tx-table-card">
            <div class="table-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h6 class="mb-0">Transaction Records</h6>
                    <small class="text-muted">Filter လုပ်ထားသော records များ</small>
                </div>
                <span class="badge bg-label-primary record-badge">
                    Records: <span id="total_count">0</span>
                </span>
            </div>

            <div class="table-responsive">
                <table id="transactions-table" class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Fee Type</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Fee</th>
                            <th class="text-end">Cash Impact</th>
                            <th class="text-end">EMoney Impact</th>
                            <th class="text-end">Cash Profit</th>
                            <th class="text-end">EMoney Profit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end total-label">
                                Grand Total (<span id="footer-count">0</span> records)
                            </th>
                            <th class="text-end" id="footer-amount">0</th>
                            <th class="text-end" id="footer-fee">0</th>
                            <th class="text-end" id="footer-cash-impact">0</th>
                            <th class="text-end" id="footer-emoney-impact">0</th>
                            <th class="text-end" id="footer-cash-profit">0</th>
                            <th class="text-end" id="footer-emoney-profit">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.transaction-data-config')
@endpush
