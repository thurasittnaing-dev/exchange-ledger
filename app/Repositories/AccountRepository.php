<?php

namespace App\Repositories;

use App\Interfaces\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

class AccountRepository implements AccountRepositoryInterface
{
    public function getData(): Builder
    {
        return Account::query()->with('bankType');
    }

    public function createAccount(array $data): Account
    {
        return Account::create($data);
    }

    public function updateAccount(Account $account, array $data): Account
    {
        $account->update($data);

        return $account;
    }

    public function deleteAccount(Account $account): void
    {
        $account->delete();
    }
}
