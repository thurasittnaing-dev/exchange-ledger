@extends('layouts.app')

@section('title', 'User Create')
@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">User @if ($method == 'POST')
                        Create
                    @else
                        Update
                    @endif Form</h5>
            </div>

            {{-- Added enctype for file upload --}}
            <form method="POST" action="{{ $route }}" id="user-form" enctype="multipart/form-data">
                @csrf
                @method($method)

                <div class="card-body">

                    {{-- Profile Image Section --}}
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="avatar-wrapper" onclick="document.getElementById('profile_image').click()">
                                <!-- Preview Image -->
                                <img id="profile-preview"
                                    src="{{ $user?->profile_image ? route('users.showProfile', $user) : 'https://ui-avatars.com/api/?name=User&background=2C5AA0&color=fff&size=120' }}"
                                    alt="Profile Preview" class="rounded-circle avatar-preview">

                                <!-- Camera Badge -->
                                @include('partials.camera-badge')
                            </div>

                            <div class="mt-2">
                                <label class="form-label text-muted small">Allowed JPG, PNG. Max size of 10MB</label>
                                <!-- Hidden File Input -->
                                <input type="file" id="profile_image" name="profile_image" class="d-none"
                                    accept="image/png, image/jpeg, image/gif, image/webp" onchange="previewImage(event)">
                            </div>

                            @error('profile_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Full Name --}}
                        <div class="col-md-4">
                            <x-form.input name="name" label="Name" placeholder="Enter user full name" :require="true"
                                :value="$user?->name" />
                        </div>

                        {{-- Username --}}
                        <div class="col-md-4">
                            <x-form.input name="username" label="Username" placeholder="Give a unique username"
                                :require="true" :value="$user?->username" />
                        </div>

                        {{-- Email --}}
                        <div class="col-md-4">
                            <x-form.input type="email" name="email" label="Email" placeholder="Enter an email"
                                :require="false" :value="$user?->email" />
                        </div>

                        @if ($method == 'POST')
                            {{-- Password --}}
                            <div class="col-md-4">
                                <x-form.input type="password" name="password" label="Password" placeholder="Enter password"
                                    :require="true" />
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-4">
                                <x-form.input type="password" name="password_confirmation" label="Confirm password"
                                    placeholder="Enter Confirm password" :require="true" />
                            </div>
                        @endif

                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2 border-top">

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <x-form.submit-btn :icon="true" formId="user-form" :label="$btnLabel" />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('profile-preview');

            // Ensure a file was actually selected
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                // Update the image source once the file is read
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        (function initUserLocationSelects() {
            if (typeof window.jQuery === 'undefined') {
                return setTimeout(initUserLocationSelects, 50);
            }

            window.jQuery(function($) {
                const $divisionSelect = $('select[name="division_id"]');
                const $districtSelect = $('select[name="district_id"]');
                const selectedDistrictId = @json($user?->district_id);
                const districtsUrlTemplate = @json(route('districts.filtered', ['division_id' => '__DIVISION_ID__'], false));

                function initSelect2($element) {
                    if ($element.length && !$element.hasClass('select2-hidden-accessible')) {
                        $element.select2({
                            allowClear: false,
                            width: '100%',
                        });
                    }
                }

                function destroySelect2($element) {
                    if ($element.hasClass('select2-hidden-accessible')) {
                        $element.select2('destroy');
                    }
                }

                function populateDistrictSelect(districts, preserveDistrictId = null) {
                    destroySelect2($districtSelect);
                    $districtSelect.empty().append('<option value="">Choose</option>');

                    if (districts && districts.length > 0) {
                        districts.forEach(function(district) {
                            $districtSelect.append(
                                $('<option>', {
                                    value: district.id,
                                    text: district.name_mm,
                                })
                            );
                        });

                        if (preserveDistrictId) {
                            $districtSelect.val(String(preserveDistrictId));
                        }
                    } else {
                        $districtSelect.append(
                            '<option value="" disabled selected>No districts available</option>'
                        );
                    }

                    initSelect2($districtSelect);
                    $districtSelect.trigger('change');
                }

                function loadDistricts(divisionId, preserveDistrictId = null) {
                    if (!divisionId) {
                        populateDistrictSelect([]);
                        return;
                    }

                    $.ajax({
                        url: districtsUrlTemplate.replace('__DIVISION_ID__', divisionId),
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        success: function(data) {
                            populateDistrictSelect(data, preserveDistrictId);
                        },
                        error: function(xhr) {
                            console.error('Error fetching districts:', xhr.status, xhr
                                .responseText);
                            populateDistrictSelect([]);
                        },
                    });
                }

                $divisionSelect.on('change select2:select', function() {
                    loadDistricts($(this).val());
                });

                const initialDivisionId = $divisionSelect.val();
                if (initialDivisionId) {
                    loadDistricts(initialDivisionId, selectedDistrictId);
                }
            });
        })();
    </script>
@endpush
