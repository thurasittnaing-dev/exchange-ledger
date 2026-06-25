<?php

namespace App\DataTables;

use App\Enums\TransactionType;
use App\Filters\TransactionFilter;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    public function __construct(
        private readonly TransactionFilter $filter,
        private readonly TransactionService $service
    ) {}

    public function query(): Builder
    {
        return $this->filter->apply($this->service->getData());
    }

    public function dataTable(Builder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('account_label', function (Transaction $transaction): string {
                $name = $transaction->account?->name ?? '-';
                $bank = $transaction->account?->bankType?->name ?? '-';

                return $name . ' (' . $bank . ')';
            })
            ->addColumn('type_badge', function (Transaction $transaction): string {
                $isCashIn = $transaction->type === TransactionType::CASH_IN;
                $class = $isCashIn ? 'bg-label-success' : 'bg-label-danger';
                $icon = $isCashIn ? 'ti-arrow-down-left' : 'ti-arrow-up-right';

                return '<span class="badge ' . $class . '"><i class="ti ' . $icon . ' me-1"></i>'
                    . ($transaction->type?->label() ?? '-') . '</span>';
            })
            ->addColumn('type_label', fn (Transaction $transaction) => $transaction->type?->label() ?? '-')
            ->addColumn('fee_type_label', fn (Transaction $transaction) => $transaction->fee_type?->label() ?? '-')
            ->addColumn('amount_formatted', fn (Transaction $transaction) => number_format($transaction->amount))
            ->addColumn('fee_amount_formatted', fn (Transaction $transaction) => number_format($transaction->fee_amount))
            ->addColumn('cash_impact_formatted', fn (Transaction $transaction) => number_format($transaction->total_cash_impact))
            ->addColumn('emoney_impact_formatted', fn (Transaction $transaction) => number_format($transaction->total_emoney_impact))
            ->addColumn('fee_cash_profit_formatted', fn (Transaction $transaction) => number_format($transaction->fee_cash_profit))
            ->addColumn('fee_emoney_profit_formatted', fn (Transaction $transaction) => number_format($transaction->fee_emoney_profit))
            ->addColumn('created_at_formatted', fn (Transaction $transaction) => dateFormat($transaction->created_at, true))
            ->rawColumns(['type_badge'])
            ->withQuery('summary', fn ($q) => $this->service->summary($q));
    }
}
