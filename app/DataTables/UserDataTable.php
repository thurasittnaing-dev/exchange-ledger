<?php

namespace App\DataTables;

use App\Filters\UserFilter;
use App\Models\Management\Permission;
use App\Models\Management\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function __construct(
        private readonly UserFilter $filter,
        private readonly UserService $service
    ) {}

    public function query(): Builder
    {
        return $this->service->getData(['division', 'district']);
    }

    public function dataTable(Builder $query): EloquentDataTable
    {
        $query = $this->filter->apply($query);
        $permissions = Permission::get()->groupBy('module');
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('status', function (User $user) {
                return view('partials.users.status', compact('user'))->render();
            })
            ->addColumn('location', function (User $user) {
                return view('partials.location', ['data' => $user])->render();
            })
            ->addColumn('position', function (User $user) {
                return view('partials.users.position', compact('user'))->render();
            })
            ->addColumn('permission', function (User $user) use ($permissions) {
                return view('partials.users.permission', compact('user', 'permissions'))->render();
            })
            ->editColumn('username', function (User $user) {
                return view('partials.users.username', compact('user'))->render();
            })
            ->editColumn('created_at', function (User $user) {
                return view('partials.created_at', ['data' => $user])->render();
            })
            ->editColumn('full_name', function (User $user) {
                return view('partials.users.name', compact('user'))->render();
            })
            ->addColumn('actions', function (User $user): string {
                return view('admin.users.actions', compact('user'))->render();
            })
            ->rawColumns(['status', 'actions', 'role', 'created_at', 'location', 'permission', 'full_name', 'email', 'username', 'position']);
    }
}
