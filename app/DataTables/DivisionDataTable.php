<?php

namespace App\DataTables;

use App\Filters\DivisionFilter;
use App\Models\Master\Division;
use App\Services\DivisionService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DivisionDataTable extends DataTable
{
    public function __construct(
        private readonly DivisionFilter $filter,
        private readonly DivisionService $service
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
            ->addColumn('actions', function(Division $division): string {
                return view('admin.divisions.actions', compact('division'))->render();
            })
            ->rawColumns(['actions']);
    }
}
