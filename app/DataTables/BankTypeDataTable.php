<?php

namespace App\DataTables;

use App\Enums\Provider;
use App\Filters\BankTypeFilter;
use App\Models\BankType;
use App\Services\BankTypeService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class BankTypeDataTable extends DataTable
{
    public function __construct(
        private readonly BankTypeFilter $filter,
        private readonly BankTypeService $service
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
            ->addColumn('provider_label', function (BankType $bankType): string {
                return Provider::tryFrom($bankType->provider)?->label() ?? '';
            })
            ->addColumn('actions', function (BankType $bankType): string {
                return view('admin.bank_types.actions', compact('bankType'))->render();
            })
            ->rawColumns(['actions']);
    }
}
