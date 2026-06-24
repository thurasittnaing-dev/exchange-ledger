@extends('layouts.app')

@section('title', 'Bank Types')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Bank Type Management</div>
                <div class="page-sub-header">Manage Bank Types</div>
            </x-slot:title>

            <x-slot:actions>
                @can('has-permission', 'bank-type-create')
                    <a href="{{ route('bank_types.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> အသစ်ထည့်သွင်းရန်
                    </a>
                @endcan
                @can('has-permission', 'bank-type-export')
                    <button id="export-xlsx" class="btn btn-success">
                        <i class="ti ti-file-type-xls"></i> Export
                    </button>
                @endcan
            </x-slot:actions>
        </x-datatable.header>

        <x-datatable.filter title='Bank Types Filter'>
            @include('admin.bank_types.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="bank-types-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Provider</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </x-datatable.wrapper>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.bank-type-data-config')
@endpush
