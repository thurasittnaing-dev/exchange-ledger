<div class="d-flex-col justify-content-center">

    <div>
        @can('has-permission', 'user-edit')
            <a href="{{ route('users.edit', $user) }}" class="btn-link text-dark" title="edit">
                <i class="ti ti-edit icon-font"></i>
            </a>
        @endcan

        @can('has-permission', 'user-delete')
            <x-form.delete-btn :route="route('users.destroy', $user)" />
        @endcan
    </div>
</div>
