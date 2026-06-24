<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\Exports\PermissionExport;
use App\Filters\PermissionFilter;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PermissionController extends Controller
{
    private const BASE_PATH = 'admin.permissions.';

    public function __construct(
        private readonly PermissionService $service
    ) {}

    public function index(): View
    {
        return view(self::BASE_PATH . 'index');
    }

    public function data(PermissionDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function create(): View
    {
        return view(self::BASE_PATH . 'form', [
            'route' => route('permissions.store'),
            'method' => 'POST',
            'permission' => null,
            'btnLabel' => 'Add Permission'
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->store($request->validated()),
            'permissions.index'
        );
    }

    public function edit(Request $request, Permission $permission): View
    {
        return view(self::BASE_PATH . 'form', [
            'route' => route('permissions.update', $permission),
            'method' => 'PUT',
            'permission' => $permission,
            'btnLabel' => 'Update Permission'
        ]);
    }

    public function update(UpdateRequest $request, Permission $permission): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->update($permission, $request->validated()),
            'permissions.index'
        );
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        return $this->handleServiceCall(
            fn() => $this->service->delete($permission),
            'permissions.index'
        );
    }


    public function export(
        Request $request,
        PermissionFilter $filter
    ) {
        $query = $filter->apply($this->service?->getData());
        $filename = 'အသုံးပြုသူစီမံခန့်ခွဲမှုများ_' . time() . '.xlsx';
        return Excel::download(new PermissionExport($query), $filename);
    }
}
