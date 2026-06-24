<?php

namespace App\DataTables;

use App\Enums\Provider;
use App\Filters\AccountFilter;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class AccountDataTable extends DataTable
{
    public function __construct(
        private readonly AccountFilter $filter,
        private readonly AccountService $service
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
            ->addColumn('bank_type_name', function (Account $account): string {
                return $account->bankType?->name ?? '-';
            })
            ->addColumn('provider_label', function (Account $account): string {
                $provider = $account->bankType?->provider;

                return $provider ? (Provider::tryFrom($provider)?->label() ?? $provider) : '-';
            })
            ->addColumn('balance_formatted', function (Account $account): string {
                return number_format($account->balance);
            })
            ->addColumn('actions', function (Account $account): string {
                return view('admin.accounts.actions', compact('account'))->render();
            })
            ->rawColumns(['actions']);
    }
}
