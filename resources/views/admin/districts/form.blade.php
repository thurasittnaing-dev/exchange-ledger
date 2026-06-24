@extends('layouts.app')

@section('title', 'District Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">District Create</h5>
            </div>

            <form method="POST" action="{{ $route }}" id="district-form">
                @csrf
                @method($method)

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Division --}}
                        <div class="col-md-6">
                            <x-form.select name="division_id" label="Division" :options="$divisions" class="lib-s2"
                                :require="true" :selected="$district?->division_id" />
                        </div>

                        {{-- Name Eng --}}
                        <div class="col-md-6">
                            <x-form.input name="name_en" label="District Name In English" placeholder="District Name In English" :require="true"
                                :value="$district?->name_en" />
                        </div>

                        {{-- Name MM --}}
                        <div class="col-md-6">
                            <x-form.input name="name_mm" label="District Name In Myanmar" placeholder="District Name In Myanmar" :require="true"
                                :value="$district?->name_mm" />
                        </div>

                        {{-- Code --}}
                        <div class="col-md-6">
                            <x-form.input name="code" label="Code" placeholder="Enter Code"
                                :value="$district?->code" />
                        </div>



                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2">

                    <a href="{{ route('districts.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="false" formId="district-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
