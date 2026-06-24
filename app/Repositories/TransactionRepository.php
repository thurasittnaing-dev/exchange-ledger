<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getData(): Builder
    {

        return Transaction::query()->with(['account.bankType']);
    }

    public function createTransaction(array $data): Transaction
    {
        return Transaction::create($data);
    }
}
