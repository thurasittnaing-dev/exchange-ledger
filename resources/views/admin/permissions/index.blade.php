@extends('layouts.app')

@section('title', 'Permissions')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Permission Management</div>
                <div class="page-sub-header">Manage permissions to control</div>
            </x-slot:title>

            <x-slot:actions>
                @can('has-permission', 'permission-create')
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> အသစ်ထည့်သွင်းရန်
                    </a>
                @endcan

                @can('has-permission', 'permission-export')
                    <button id="export-xlsx" class="btn btn-success">
                        <i class="ti ti-file-type-xls"></i> Export
                    </button>
                @endcan
            </x-slot:actions>
        </x-datatable.header>

        <x-datatable.filter title='Filter Permissions'>
            @include('admin.permissions.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="permissions-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </x-datatable.wrapper>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.permissions-data-config')
@endpush
