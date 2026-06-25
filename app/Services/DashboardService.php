<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AccountHistoryReferenceType;
use App\Enums\CashHistoryReferenceType;
use App\Enums\TransactionType;
use App\Filters\DashboardFilter;
use App\Models\Account;
use App\Models\AccountHistory;
use App\Models\BankType;
use App\Models\CashBalance;
use App\Models\CashHistory;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

final class DashboardService
{
    public function analytics(DashboardFilter $filter): array
    {
        $txQuery = $filter->applyTransactions(Transaction::query());

        $stats = $this->transactionStats(clone $txQuery);
        $stats['cash_in_count'] = (int) (clone $txQuery)->where('transactions.type', TransactionType::CASH_IN->value)->count();
        $stats['cash_out_count'] = (int) (clone $txQuery)->where('transactions.type', TransactionType::CASH_OUT->value)->count();
        $stats['cash_in_amount'] = (int) (clone $txQuery)->where('transactions.type', TransactionType::CASH_IN->value)->sum('transactions.amount');
        $stats['cash_out_amount'] = (int) (clone $txQuery)->where('transactions.type', TransactionType::CASH_OUT->value)->sum('transactions.amount');

        return [
            'balances' => $this->liveBalances(),
            'transactions' => $stats,
            'manual' => $this->manualStats($filter),
            'by_bank_type_chart' => $this->transactionsByBankTypeChart(clone $txQuery),
            'profit_chart' => $this->profitChart(clone $txQuery),
            'by_account' => $this->breakdownByAccount(clone $txQuery),
            'by_bank_type' => $this->breakdownByBankType(clone $txQuery),
            'by_provider' => $this->breakdownByProvider(clone $txQuery),
            'recent' => $this->recentTransactions(clone $txQuery),
        ];
    }

    public function filterOptions(): array
    {
        return [
            'accounts' => Account::query()->with('bankType')->orderBy('name')->get()
                ->mapWithKeys(fn ($a) => [
                    $a->id => $a->name . ' (' . ($a->bankType?->name ?? '-') . ')',
                ])
                ->toArray(),
            'bank_types' => BankType::query()->orderBy('name')->pluck('name', 'id'),
            'providers' => \App\Enums\Provider::options(),
            'transaction_types' => TransactionType::options(),
            'wallet_types' => [
                'emoney' => 'EMoney',
                'cash' => 'Cash',
            ],
        ];
    }

    private function liveBalances(): array
    {
        return [
            'emoney' => (int) Account::query()->sum('balance'),
            'cash' => CashBalance::amount(),
            'accounts_count' => Account::query()->count(),
            'bank_types_count' => BankType::query()->count(),
        ];
    }

    private function transactionStats($query): array
    {
        $feeCash = (int) (clone $query)->sum('transactions.fee_cash_profit');
        $feeEmoney = (int) (clone $query)->sum('transactions.fee_emoney_profit');

        return [
            'count' => (int) (clone $query)->count(),
            'amount' => (int) (clone $query)->sum('transactions.amount'),
            'fee_amount' => (int) (clone $query)->sum('transactions.fee_amount'),
            'total_cash_impact' => (int) (clone $query)->sum('transactions.total_cash_impact'),
            'total_emoney_impact' => (int) (clone $query)->sum('transactions.total_emoney_impact'),
            'fee_cash_profit' => $feeCash,
            'fee_emoney_profit' => $feeEmoney,
            'total_profit' => $feeCash + $feeEmoney,
        ];
    }

    private function manualStats(DashboardFilter $filter): array
    {
        $accountHistory = $filter->applyAccountHistories(AccountHistory::query());
        $cashHistory = $filter->applyDate(CashHistory::query());

        return [
            'account_add_count' => (int) (clone $accountHistory)
                ->where('reference_type', AccountHistoryReferenceType::MANUAL_ADD->value)->count(),
            'account_add_amount' => (int) (clone $accountHistory)
                ->where('reference_type', AccountHistoryReferenceType::MANUAL_ADD->value)->sum('amount'),
            'account_reset_count' => (int) (clone $accountHistory)
                ->where('reference_type', AccountHistoryReferenceType::MANUAL_RESET->value)->count(),
            'cash_opening_count' => (int) (clone $cashHistory)
                ->where('reference_type', CashHistoryReferenceType::OPENING->value)->count(),
            'cash_opening_amount' => (int) (clone $cashHistory)
                ->where('reference_type', CashHistoryReferenceType::OPENING->value)->sum('amount'),
            'cash_reset_count' => (int) (clone $cashHistory)
                ->where('reference_type', CashHistoryReferenceType::RESET->value)->count(),
        ];
    }

    private function transactionsByBankTypeChart($query): array
    {
        $cashIn = TransactionType::CASH_IN->value;
        $cashOut = TransactionType::CASH_OUT->value;

        $rows = (clone $query)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->join('bank_types', 'accounts.bank_type_id', '=', 'bank_types.id')
            ->select(
                'bank_types.id',
                'bank_types.name',
                DB::raw('COUNT(*) as tx_count'),
                DB::raw("SUM(CASE WHEN transactions.type = '{$cashIn}' THEN 1 ELSE 0 END) as cash_in_count"),
                DB::raw("SUM(CASE WHEN transactions.type = '{$cashOut}' THEN 1 ELSE 0 END) as cash_out_count"),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('SUM(transactions.fee_cash_profit + transactions.fee_emoney_profit) as total_profit')
            )
            ->groupBy('bank_types.id', 'bank_types.name')
            ->orderByDesc('tx_count')
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'txCount' => $rows->pluck('tx_count')->map(fn ($v) => (int) $v)->values()->toArray(),
            'cashInCount' => $rows->pluck('cash_in_count')->map(fn ($v) => (int) $v)->values()->toArray(),
            'cashOutCount' => $rows->pluck('cash_out_count')->map(fn ($v) => (int) $v)->values()->toArray(),
            'totalAmount' => $rows->pluck('total_amount')->map(fn ($v) => (int) $v)->values()->toArray(),
            'totalProfit' => $rows->pluck('total_profit')->map(fn ($v) => (int) $v)->values()->toArray(),
        ];
    }

    private function profitChart($query): array
    {
        return [
            'cash' => (int) (clone $query)->sum('transactions.fee_cash_profit'),
            'emoney' => (int) (clone $query)->sum('transactions.fee_emoney_profit'),
        ];
    }

    private function breakdownByAccount($query): array
    {
        return (clone $query)
            ->select('transactions.account_id', DB::raw('COUNT(*) as tx_count'), DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('SUM(transactions.total_cash_impact) as cash_impact'), DB::raw('SUM(transactions.total_emoney_impact) as emoney_impact'),
                DB::raw('SUM(transactions.fee_cash_profit + transactions.fee_emoney_profit) as total_profit'))
            ->groupBy('transactions.account_id')
            ->with('account.bankType')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'label' => ($row->account?->name ?? '-') . ' (' . ($row->account?->bankType?->name ?? '-') . ')',
                'tx_count' => (int) $row->tx_count,
                'total_amount' => (int) $row->total_amount,
                'cash_impact' => (int) $row->cash_impact,
                'emoney_impact' => (int) $row->emoney_impact,
                'total_profit' => (int) $row->total_profit,
            ])
            ->toArray();
    }

    private function breakdownByBankType($query): array
    {
        return (clone $query)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->join('bank_types', 'accounts.bank_type_id', '=', 'bank_types.id')
            ->select('bank_types.id', 'bank_types.name', DB::raw('COUNT(*) as tx_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('SUM(transactions.fee_cash_profit + transactions.fee_emoney_profit) as total_profit'))
            ->groupBy('bank_types.id', 'bank_types.name')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->name,
                'tx_count' => (int) $row->tx_count,
                'total_amount' => (int) $row->total_amount,
                'total_profit' => (int) $row->total_profit,
            ])
            ->toArray();
    }

    private function breakdownByProvider($query): array
    {
        return (clone $query)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->join('bank_types', 'accounts.bank_type_id', '=', 'bank_types.id')
            ->select('bank_types.provider', DB::raw('COUNT(*) as tx_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('SUM(transactions.fee_cash_profit + transactions.fee_emoney_profit) as total_profit'))
            ->groupBy('bank_types.provider')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row) => [
                'label' => \App\Enums\Provider::tryFrom($row->provider)?->label() ?? $row->provider,
                'tx_count' => (int) $row->tx_count,
                'total_amount' => (int) $row->total_amount,
                'total_profit' => (int) $row->total_profit,
            ])
            ->toArray();
    }

    private function recentTransactions($query): array
    {
        return (clone $query)
            ->with('account.bankType')
            ->latest('transactions.created_at')
            ->limit(8)
            ->get()
            ->map(fn (Transaction $tx) => [
                'date' => dateFormat($tx->created_at, true),
                'account' => ($tx->account?->name ?? '-') . ' (' . ($tx->account?->bankType?->name ?? '-') . ')',
                'type' => $tx->type?->label() ?? '-',
                'type_value' => $tx->type?->value,
                'amount' => (int) $tx->amount,
                'cash_impact' => (int) $tx->total_cash_impact,
                'emoney_impact' => (int) $tx->total_emoney_impact,
                'profit' => (int) ($tx->fee_cash_profit + $tx->fee_emoney_profit),
            ])
            ->toArray();
    }
}
