<div class="d-flex-col justify-content-center">

    @can('has-permission', 'division-edit')
        <a href="{{ route('divisions.edit', $division) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'division-delete')
        <x-form.delete-btn :route="route('divisions.destroy', $division)" />
    @endcan

</div>
