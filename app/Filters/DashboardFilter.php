<?php

declare(strict_types=1);

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class DashboardFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function dateFrom(): ?Carbon
    {
        if (! $this->request->date_from) {
            return null;
        }

        return Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay();
    }

    public function dateTo(): ?Carbon
    {
        if (! $this->request->date_to) {
            return null;
        }

        return Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay();
    }

    public function walletType(): string
    {
        return in_array($this->request->wallet_type, ['emoney', 'cash'], true)
            ? $this->request->wallet_type
            : 'all';
    }

    public function values(): array
    {
        return [
            'date_from' => $this->request->date_from ?? '',
            'date_to' => $this->request->date_to ?? '',
            'wallet_type' => $this->walletType() === 'all' ? '' : $this->walletType(),
            'account_id' => $this->request->account_id ?? '',
            'bank_type_id' => $this->request->bank_type_id ?? '',
            'provider' => $this->request->provider ?? '',
            'transaction_type' => $this->request->transaction_type ?? '',
        ];
    }

    public function applyDate(Builder $query, string $column = 'created_at'): Builder
    {
        $query->when($this->dateFrom(), fn ($q) => $q->where($column, '>=', $this->dateFrom()));
        $query->when($this->dateTo(), fn ($q) => $q->where($column, '<=', $this->dateTo()));

        return $query;
    }

    public function applyTransactions(Builder $query): Builder
    {
        $this->applyDate($query, 'transactions.created_at');

        $query->when($this->request->account_id, fn ($q) => $q->where('transactions.account_id', $this->request->account_id));

        $query->when($this->request->transaction_type, fn ($q) => $q->where('transactions.type', $this->request->transaction_type));

        $query->when($this->request->bank_type_id, function ($q) {
            $q->whereHas('account', fn ($q) => $q->where('bank_type_id', $this->request->bank_type_id));
        });

        $query->when($this->request->provider, function ($q) {
            $q->whereHas('account.bankType', fn ($q) => $q->where('provider', $this->request->provider));
        });

        return $query;
    }

    public function applyAccountHistories(Builder $query): Builder
    {
        $this->applyDate($query);

        $query->when($this->request->account_id, fn ($q) => $q->where('account_id', $this->request->account_id));

        $query->when($this->request->bank_type_id, function ($q) {
            $q->whereHas('account', fn ($q) => $q->where('bank_type_id', $this->request->bank_type_id));
        });

        $query->when($this->request->provider, function ($q) {
            $q->whereHas('account.bankType', fn ($q) => $q->where('provider', $this->request->provider));
        });

        return $query;
    }
}
