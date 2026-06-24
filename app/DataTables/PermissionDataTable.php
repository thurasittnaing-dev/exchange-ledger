<?php

namespace App\DataTables;

use App\Filters\PermissionFilter;
use App\Models\Management\Permission;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
{
    public function __construct(
        private readonly PermissionFilter $filter,
        private readonly PermissionService $service
    ) {}

    public function query(): Builder
    {
        return $this->service->getData();
    }

    public function dataTable(Builder $query): EloquentDataTable
    {
        $query = $this->filter->apply($query);
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function (Permission $permission) {
                return view('partials.created_at', ['data' => $permission])->render();
            })
            ->addColumn('actions', function (Permission $permission): string {
                return view('admin.permissions.actions', compact('permission'))->render();
            })
            ->rawColumns(['actions', 'created_at']);
    }
}
