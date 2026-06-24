<?php

namespace App\DataTables;

use App\Filters\ActivityGroupFilter;
use App\Models\Master\ActivityGroup;
use App\Services\ActivityGroupService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class ActivityGroupDataTable extends DataTable
{
    public function __construct(
        private readonly ActivityGroupFilter $filter,
        private readonly ActivityGroupService $service
    ) {}

    public function query(): Builder
    {
        return $this->service->getData()->whereNull('parent_id');
    }

    public function dataTable(Builder $query): EloquentDataTable
    {
        $query = $this->filter->apply($query);
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('is_active', function (ActivityGroup $activityGroup) {
                return view('partials.activity_groups.status', compact('activityGroup'))->render();
            })
            ->editColumn('created_at', function (ActivityGroup $activityGroup) {
                return view('partials.created_at', ['data' => $activityGroup])->render();
            })
            ->addColumn('actions', function (ActivityGroup $activityGroup): string {
                return view('admin.activity_groups.actions', compact('activityGroup'))->render();
            })
            ->rawColumns(['is_active', 'created_at', 'actions']);
    }
}
