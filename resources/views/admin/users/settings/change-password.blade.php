<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Change Password</h5>
        </div>

        <form method="POST" action="{{ route('users.passwordChange') }}" id="change-password-form">
            @csrf

            <div class="card-body">

                <div class="row g-3">

                    {{-- Password --}}
                    <div class="col-md-6">
                        <x-form.input type="password" name="password" label="Password" placeholder="Enter password"
                            :require="true" />
                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-md-6">
                        <x-form.input type="password" name="password_confirmation" label="Confirm password"
                            placeholder="Enter Confirm password" :require="true" />
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="card-footer bg-white d-flex justify-content-end gap-2">

                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <x-form.submit-btn :icon="false" formId="change-password-form" label="Change Password" />

            </div>
        </form>
    </div>
</div>
