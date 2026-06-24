<?php

namespace App\Interfaces;

use App\Models\CashHistory;
use Illuminate\Database\Eloquent\Builder;

interface CashMoneyRepositoryInterface
{
    public function getManualData(): Builder;

    public function createHistory(array $data): CashHistory;
}
