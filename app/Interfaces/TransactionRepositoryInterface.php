<?php

namespace App\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

interface TransactionRepositoryInterface
{
    public function getData(): Builder;

    public function createTransaction(array $data): Transaction;
}
