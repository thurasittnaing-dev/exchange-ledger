<div class="d-flex-col justify-content-center">

    @can('has-permission', 'activity-group-create')
        <x-form.add-sub :route="route('activity_groups.add_sub_activity_groups', $activityGroup)"
            title="Add Sub Activity Group" />
    @endcan

    @can('has-permission', 'activity-group-edit')
        <a href="{{ route('activity_groups.edit', $activityGroup) }}" class="btn-link text-dark" title="edit">
            <i class="ti ti-edit icon-font"></i>
        </a>
    @endcan

    @can('has-permission', 'activity-group-delete')
        <x-form.delete-btn :route="route('activity_groups.destroy', $activityGroup)" />
    @endcan

</div>
