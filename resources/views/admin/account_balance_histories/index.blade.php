@extends('layouts.app')

@section('title', 'Account Balance History')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Account Balance History</div>
                <div class="page-sub-header">Manual Add & Reset Records</div>
            </x-slot:title>

            <x-slot:actions>
                @can('has-permission', 'account-balance-create')
                    <a href="{{ route('account_balance_histories.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add / Reset Balance
                    </a>
                @endcan
                @can('has-permission', 'account-balance-export')
                    <button id="export-xlsx" class="btn btn-success">
                        <i class="ti ti-file-type-xls"></i> Export
                    </button>
                @endcan
            </x-slot:actions>
        </x-datatable.header>

        <x-datatable.filter title='Balance History Filter'>
            @include('admin.account_balance_histories.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="account-balance-histories-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Account</th>
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
    @include('admin.datatable-configs.account-balance-history-data-config')
@endpush
