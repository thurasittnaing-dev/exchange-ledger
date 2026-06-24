<?php

declare(strict_types=1);

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class AccountBalanceHistoryFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $query->when($this->request->account_id, function ($q) {
            $q->where('account_id', $this->request->account_id);
        });

        $query->when($this->request->reference_type, function ($q) {
            $q->where('reference_type', $this->request->reference_type);
        });

        $query->when($this->request->date_from, function ($q) {
            $date = Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay();
            $q->where('created_at', '>=', $date);
        });

        $query->when($this->request->date_to, function ($q) {
            $date = Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay();
            $q->where('created_at', '<=', $date);
        });

        return $query;
    }
}
