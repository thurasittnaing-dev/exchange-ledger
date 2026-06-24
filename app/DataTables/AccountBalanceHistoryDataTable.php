<?php

namespace App\DataTables;

use App\Filters\AccountBalanceHistoryFilter;
use App\Models\AccountHistory;
use App\Services\AccountBalanceHistoryService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class AccountBalanceHistoryDataTable extends DataTable
{
    public function __construct(
        private readonly AccountBalanceHistoryFilter $filter,
        private readonly AccountBalanceHistoryService $service
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
            ->addColumn('account_name', function (AccountHistory $history): string {
                return $history->account?->name . ' (' . $history->account?->bankType?->name . ')'  ?? '-';
            })
            ->addColumn('reference_type_label', function (AccountHistory $history): string {
                return $history->reference_type?->label() ?? '-';
            })
            ->addColumn('action_label', function (AccountHistory $history): string {
                return $history->action?->label() ?? '-';
            })
            ->addColumn('amount_formatted', function (AccountHistory $history): string {
                return number_format($history->amount);
            })
            ->addColumn('running_balance_formatted', function (AccountHistory $history): string {
                return number_format($history->running_balance);
            })
            ->addColumn('created_at_formatted', function (AccountHistory $history): string {
                return dateFormat($history->created_at, true);
            });
    }
}
