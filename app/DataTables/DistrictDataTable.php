<?php

namespace App\DataTables;

use App\Filters\DistrictFilter;
use App\Models\Master\District;
use App\Services\DistrictService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DistrictDataTable extends DataTable
{
    public function __construct(
         private readonly DistrictFilter $filter,
        private readonly DistrictService $service
    ) {}

    public function query(): Builder
    {
        $query = $this->service->getData(['division']);

        return $this->filter->apply($query);
    }

    public function dataTable(Builder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('division_name', function (District $district) {
                return $district->division->name_mm ?? '-';
            })
            ->addColumn('actions', function (District $district): string {
                return view('admin.districts.actions', compact('district'))->render();
            })
            ->rawColumns(['division_name','actions']);
    }
}
