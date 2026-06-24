<?php

namespace App\Interfaces;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

interface AccountRepositoryInterface
{
    public function getData(): Builder;

    public function createAccount(array $data): Account;

    public function updateAccount(Account $account, array $data): Account;

    public function deleteAccount(Account $account): void;
}
