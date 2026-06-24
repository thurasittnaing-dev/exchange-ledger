<?php

namespace App\Repositories;

use App\Enums\AccountHistoryReferenceType;
use App\Interfaces\AccountBalanceHistoryRepositoryInterface;
use App\Models\AccountHistory;
use Illuminate\Database\Eloquent\Builder;

class AccountBalanceHistoryRepository implements AccountBalanceHistoryRepositoryInterface
{
    public function getManualData(): Builder
    {
        return AccountHistory::query()
            ->with('account')
            ->whereIn('reference_type', AccountHistoryReferenceType::manualValues());
    }

    public function createHistory(array $data): AccountHistory
    {
        return AccountHistory::create($data);
    }
}
