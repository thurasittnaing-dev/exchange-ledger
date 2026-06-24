@extends('layouts.app')

@section('title', 'Sub Activity Group Create')

@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    Add Sub Activity Group For {{ $activityGroup->name }}
                </h5>
                @if ($activityGroup->parent)
                    <small class="text-muted">
                        Parent: {{ $activityGroup->parent->name }}
                    </small>
                @endif
            </div>

            <form method="POST" action="{{ $route }}" id="sub-activity-group-form">
                @csrf
                @method($method)

                <div class="card-body">
                    <div class="row g-3">
                        <input type="hidden" name="parent_id" value="{{ $activityGroup->id }}">

                        <div class="col-md-6">
                            <x-form.input name="name" label="Sub Activity Group Name"
                                placeholder="Enter Sub Activity Group Name" :require="true"
                                :value="$subActivityGroup?->name" />
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_active" type="checkbox" role="switch"
                                    id="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Status</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <a href="{{ $activityGroup->parent_id ? route('activity_groups.add_sub_activity_groups', $activityGroup->parent_id) : route('activity_groups.index') }}"
                        class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="sub-activity-group-form" :label="$btnLabel" />
                </div>
            </form>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h6 class="text-primary text-end">
                    Total: <span id="total_count">0</span>
                </h6>

                <table id="sub-activity-groups-table" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    @include('admin.datatable-configs.sub-activity-group-data-config')
@endpush
