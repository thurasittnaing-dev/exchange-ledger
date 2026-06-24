<?php

namespace App\Repositories;

use App\Enums\CashHistoryReferenceType;
use App\Interfaces\CashMoneyRepositoryInterface;
use App\Models\CashHistory;
use Illuminate\Database\Eloquent\Builder;

class CashMoneyRepository implements CashMoneyRepositoryInterface
{
    public function getManualData(): Builder
    {
        return CashHistory::query()
            ->whereIn('reference_type', CashHistoryReferenceType::manualValues());
    }

    public function createHistory(array $data): CashHistory
    {
        return CashHistory::create($data);
    }
}
