@extends('layouts.app')

@section('title', 'Department Types')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <x-datatable.header>
            <x-slot:title>
                <div class="page-main-header">Activity Group Management</div>
                <div class="page-sub-header">Manage Activity Group</div>
            </x-slot:title>

            <x-slot:actions>
                @can('has-permission', 'activity-group-create')
                    <a href="{{ route('activity_groups.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> အသစ်ထည့်သွင်းရန်
                    </a>
                @endcan

                @can('has-permission', 'activity-group-export')
                    <button id="export-xlsx" class="btn btn-success">
                        <i class="ti ti-file-type-xls"></i> Export
                    </button>
                @endcan
            </x-slot:actions>
        </x-datatable.header>

        <x-datatable.filter title='Filter Activity Group'>
            @include('admin.activity_groups.filter')
        </x-datatable.filter>

        <x-datatable.wrapper>
            <h6 class="me-3 d-flex justify-content-end text-primary">
                Total:<span id="total_count" class="ms-1">0</span>
            </h6>
            <table id="activity-groups-table" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </x-datatable.wrapper>
    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.activity-group-data-config')
@endpush
