<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityGroupDataTable;
use App\DataTables\SubActivityGroupDataTable;
use App\Enums\Status;
use App\Exports\ActivityGroupExport;
use App\Filters\ActivityGroupFilter;
use App\Http\Requests\ActivityGroup\StoreRequest;
use App\Http\Requests\ActivityGroup\UpdateRequest;
use App\Models\Master\ActivityGroup;
use App\Services\ActivityGroupService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ActivityGroupController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.activity_groups.';

    public function __construct(
        private readonly ActivityGroupService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'activity-group-list');

        return view(self::BASE_PATH . 'index', [
            'status' => Status::options(),
        ]);
    }

    public function data(ActivityGroupDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'activity-group-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('activity_groups.store'),
            'method' => 'POST',
            'btnLabel' => 'Create Activity Group',
            'activityGroup' => null,
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'activity-group-create');

        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'activity_groups.index'
        );
    }

    public function edit(ActivityGroup $activityGroup): View
    {
        $this->authorize('has-permission', 'activity-group-edit');

        return view(self::BASE_PATH . 'form', [
            'route' => route('activity_groups.update', $activityGroup),
            'method' => 'PUT',
            'btnLabel' => 'Update Activity Group',
            'activityGroup' => $activityGroup,
        ]);
    }

    public function update(UpdateRequest $request, ActivityGroup $activityGroup): RedirectResponse
    {
        $this->authorize('has-permission', 'activity-group-edit');

        $result = $this->service->update($activityGroup, $request->validated());

        if (!($result['status'] ?? false)) {
            return back()
                ->withInput()
                ->with('error', $result['message'] ?? __('An unexpected error occurred.'));
        }

        if ($activityGroup->parent_id) {
            return redirect()
                ->route('activity_groups.add_sub_activity_groups', $activityGroup->parent_id)
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('activity_groups.index')
            ->with('success', $result['message']);
    }

    public function destroy(ActivityGroup $activityGroup): RedirectResponse
    {
        $this->authorize('has-permission', 'activity-group-delete');

        $parentId = $activityGroup->parent_id;
        $result = $this->service->delete($activityGroup);

        if (!($result['status'] ?? false)) {
            return back()->with('error', $result['message'] ?? __('An unexpected error occurred.'));
        }

        if ($parentId) {
            return redirect()
                ->route('activity_groups.add_sub_activity_groups', $parentId)
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('activity_groups.index')
            ->with('success', $result['message']);
    }

    public function export(ActivityGroupFilter $filter)
    {
        $this->authorize('has-permission', 'activity-group-export');
        $query = $filter->apply($this->service->getData())->whereNull('parent_id');
        $filename = 'စစ်ဆှုအမျိုးအစားများ_' . time() . '.xlsx';

        return Excel::download(new ActivityGroupExport($query), $filename);
    }

    public function addSubActivityGroups(ActivityGroup $activityGroup): View
    {
        $this->authorize('has-permission', 'activity-group-create');

        return view(self::BASE_PATH . 'add_sub_activity_group', [
            'route' => route('activity_groups.store_sub_activity_groups', $activityGroup),
            'method' => 'POST',
            'activityGroup' => $activityGroup,
            'subActivityGroup' => null,
            'btnLabel' => 'Create Sub Activity Group',
        ]);
    }

    public function storeSubActivityGroups(StoreRequest $request, ActivityGroup $activityGroup): RedirectResponse
    {
        $this->authorize('has-permission', 'activity-group-create');
        $this->service->storeSubActivityGroup($request->validated(), $activityGroup);

        return redirect()
            ->route('activity_groups.add_sub_activity_groups', $activityGroup->id)
            ->with('success', 'Sub Activity Group Created Successfully.');
    }

    public function subData(SubActivityGroupDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }
}
