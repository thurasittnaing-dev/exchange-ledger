<?php

namespace App\Interfaces;

use App\Models\AccountHistory;
use Illuminate\Database\Eloquent\Builder;

interface AccountBalanceHistoryRepositoryInterface
{
    public function getManualData(): Builder;

    public function createHistory(array $data): AccountHistory;
}
