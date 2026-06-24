<a href="#" class="text text-primary" data-bs-toggle="modal" data-bs-target="#permissionModal-{{ $user->id }}">
    <i class="ti ti-shield"></i> Manage
</a>

<div class="modal fade" id="permissionModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-dark"><b>Manage User Permissions</b></h5>
                    <div class="text-muted">
                        <smal>Configure permissions for {{ $user->full_name }}</smal>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('users.permissionUpdate', $user) }}"
                id="update-form-{{ $user->id }}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        @foreach ($permissions as $moduleName => $modulePermissions)
                            <div class="col-4 mb-3">
                                <div class="card p-3 fancy-shadow">
                                    <p class="text-dark m-0 p-0">{{ $moduleName }} Module</p>
                                    <hr />
                                    @foreach ($modulePermissions as $permission)
                                        <div class="form-check">
                                            <label class="form-check-label user-select-none">
                                                <input name="permission_ids[]" class="form-check-input" type="checkbox"
                                                    value="{{ $permission->id }}"
                                                    @if ($user->permissions->contains('id', $permission->id)) checked @endif>
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <x-form.submit-btn :icon="true" formId="update-form-{{ $user->id }}"
                        label="Save Permission" />
                </div>
            </form>
        </div>
    </div>
</div>
