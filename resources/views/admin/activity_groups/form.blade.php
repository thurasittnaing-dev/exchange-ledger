@extends('layouts.app')

@section('title', 'Department Type Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Activity Group Create</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="activity-groups-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-12">
                            <x-form.input name="name" label="Name" placeholder="Enter Name" :require="true"
                                :value="$activityGroup?->name" />
                        </div>

                        {{-- Status --}}
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_active" type="checkbox" role="switch"
                                    id="is_active" value="1" {{ $activityGroup?->is_active == '0' ? '' : 'checked' }}>
                                <label class="form-check-label" for="is_active">Status</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ $activityGroup?->parent_id ? route('activity_groups.add_sub_activity_groups', $activityGroup->parent_id) : route('activity_groups.index') }}"
                        class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="activity-groups-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
