<?php

namespace App\DataTables;

use App\Filters\CashMoneyFilter;
use App\Models\CashHistory;
use App\Services\CashMoneyService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class CashMoneyDataTable extends DataTable
{
    public function __construct(
        private readonly CashMoneyFilter $filter,
        private readonly CashMoneyService $service
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
            ->addColumn('reference_type_label', function (CashHistory $history): string {
                return $history->reference_type?->label() ?? '-';
            })
            ->addColumn('action_label', function (CashHistory $history): string {
                return $history->action?->label() ?? '-';
            })
            ->addColumn('amount_formatted', function (CashHistory $history): string {
                return number_format($history->amount);
            })
            ->addColumn('running_balance_formatted', function (CashHistory $history): string {
                return number_format($history->running_balance);
            })
            ->addColumn('created_at_formatted', function (CashHistory $history): string {
                return dateFormat($history->created_at, true);
            });
    }
}
