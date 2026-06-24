<div class="d-flex gap-2">
    @can('has-permission', 'permission-edit')
        <a href="{{ route('permissions.edit', $permission) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'permission-delete')
        <x-form.delete-btn :route="route('permissions.destroy', $permission)" />
    @endcan
</div>
