@extends('layouts.app')

@section('title', 'Division Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Division Create</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="division-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Name_ENG --}}
                        <div class="col-md-6">
                            <x-form.input name="name_en" label="Division Name In English" placeholder="Enter Division Name In English" :require="true"
                                :value="$division?->name_en" />
                        </div>

                        {{-- Name_MM --}}
                        <div class="col-md-6">
                            <x-form.input name="name_mm" label="Division Name In Myanmar" placeholder="Enter Division Name In Myanmar" :require="true"
                                :value="$division?->name_mm" />
                        </div>

                        {{-- Code --}}
                        <div class="col-md-6">
                            <x-form.input name="code" label="Code" placeholder="Enter Code" :require="true"
                                :value="$division?->code" />
                        </div>

                        {{-- Level --}}
                        <div class="col-md-6">
                            <x-form.input name="level" label="Level" placeholder="Enter Level"
                                :value="$division?->level" />
                        </div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('divisions.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="division-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
