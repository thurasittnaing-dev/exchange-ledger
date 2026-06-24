@if ($departmentType->is_active)
    <span class="badge rounded-pill bg-label-success">Active</span>
@else
    <span class="badge rounded-pill bg-label-danger">Inactive</span>
@endif
