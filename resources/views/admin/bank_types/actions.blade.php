<div class="d-flex-col justify-content-center">

    @can('has-permission', 'bank-type-edit')
        <a href="{{ route('bank_types.edit', $bankType) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'bank-type-delete')
        <x-form.delete-btn :route="route('bank_types.destroy', $bankType)" />
    @endcan

</div>
