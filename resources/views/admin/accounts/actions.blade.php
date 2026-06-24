<div class="d-flex-col justify-content-center">

    @can('has-permission', 'account-edit')
        <a href="{{ route('accounts.edit', $account) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'account-delete')
        <x-form.delete-btn :route="route('accounts.destroy', $account)" />
    @endcan

</div>
