<div class="d-flex-col justify-content-center">

    @can('has-permission', 'district-edit')
        <a href="{{ route('districts.edit', $district) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'district-delete')
        <x-form.delete-btn :route="route('districts.destroy', $district)" />
    @endcan

</div>
