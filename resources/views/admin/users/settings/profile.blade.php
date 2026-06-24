<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom text-center">
            <h5 class="fw-bold text-dark mb-1">User Profile Details</h5>

            <div class="position-relative d-inline-block mt-3">
                <img id="profile-preview"
                    src="{{ $user?->profile_image ? route('users.showProfile', $user) : 'https://ui-avatars.com/api/?name=User&background=2C5AA0&color=fff&size=120' }}"
                    alt="Profile Preview"
                    class="rounded-circle shadow-sm border border-2 border-white"
                    style="width: 120px; height: 120px; object-fit: cover; object-position: center;">
            </div>
        </div>

        <div class="card-body px-4 py-4">

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">Full Name</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->full_name }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">User Name</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->username }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">Email Address</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->email }}
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">Role</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->role }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">Division</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->division?->name_mm ?? 'Not Set' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.75rem;">District</label>
                    <div class="p-2 rounded-3 border text-dark" style="background-color: #f8fafc !important;">
                        {{ $user->district?->name_mm ?? 'Not Set' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
