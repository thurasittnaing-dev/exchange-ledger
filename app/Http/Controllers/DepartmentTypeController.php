<?php

namespace App\Http\Controllers;

use App\DataTables\DepartmentTypeDataTable;
use App\DataTables\SubDepartmentDataTable;
use App\Exports\DepartmentTypeExport;
use App\Filters\DepartmentTypeFilter;
use App\Http\Requests\DepartmentType\StoreRequest;
use App\Models\Master\Department;
use App\Services\DepartmentTypeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentTypeController extends Controller
{
    use AuthorizesRequests;

    private const BASE_PATH = 'admin.department_types.';

    public function __construct(
        private readonly DepartmentTypeService $service
    ) {}

    public function index(): View
    {
        $this->authorize('has-permission', 'department-type-list');
        $department_types = Department::whereNull('parent_id')->pluck('name', 'id')->toArray();

        return view(self::BASE_PATH . 'index', [
            'department_types' => $department_types
        ]);
    }

    public function data(DepartmentTypeDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        $this->authorize('has-permission', 'department-type-create');

        return view(self::BASE_PATH . 'form', [
            'route' => route('department_types.store'),
            'method' => 'POST',
            'department_type' => null,
            'btnLabel' => 'Create Department Type'
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('has-permission', 'department-type-create');

        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'department_types.index'
        );
    }

    public function edit(Department $departmentType): View
    {
        $this->authorize('has-permission', 'department-type-edit');

        return view(self::BASE_PATH . 'form', [
            'route' => route('department_types.update', $departmentType),
            'method' => 'PUT',
            'department_type' => $departmentType,
            'btnLabel' => 'Update Department Type'
        ]);
    }

    public function update(StoreRequest $request, Department $departmentType): RedirectResponse
    {
        $this->authorize('has-permission', 'department-type-edit');

        $result = $this->service->update($departmentType, $request->validated());

        if (!($result['status'] ?? false)) {
            return back()
                ->withInput()
                ->with('error', $result['message'] ?? __('An unexpected error occurred.'));
        }

        if ($departmentType->parent_id) {
            return redirect()
                ->route('department_types.add_sub_departments', $departmentType->parent_id)
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('department_types.index')
            ->with('success', $result['message']);
    }

    public function destroy(Department $departmentType): RedirectResponse
    {
        $this->authorize('has-permission', 'department-type-delete');

        $parentId = $departmentType->parent_id;
        $result = $this->service->delete($departmentType);

        if (!($result['status'] ?? false)) {
            return back()->with('error', $result['message'] ?? __('An unexpected error occurred.'));
        }

        if ($parentId) {
            return redirect()
                ->route('department_types.add_sub_departments', $parentId)
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('department_types.index')
            ->with('success', $result['message']);
    }

    public function export(DepartmentTypeFilter $filter)
    {
        $this->authorize('has-permission', 'department-type-export');
        $query = $filter->apply($this->service->getData())->whereNull('parent_id');
        $filename = 'department_types_' . time() . '.xlsx';

        return Excel::download(new DepartmentTypeExport($query), $filename);
    }

    public function addSubDepartments(Department $departmentType): View
    {
        $this->authorize('has-permission', 'department-type-create');

        return view(self::BASE_PATH . 'add_sub_department', [
            'route' => route('department_types.store_sub_departments', $departmentType),
            'method' => 'POST',
            'department_type' => $departmentType,
            'subDepartment' => null,
            'btnLabel' => 'Create Sub Department',
        ]);
    }

    public function storeSubDepartments(StoreRequest $request, Department $departmentType): RedirectResponse
    {
        $this->authorize('has-permission', 'department-type-create');
        $this->service->storeSubDepartment($request->validated(), $departmentType);

        return redirect()
            ->route('department_types.add_sub_departments', $departmentType->id)
            ->with('success', 'Sub Department Created Successfully.');
    }

    public function subData(SubDepartmentDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }
}
