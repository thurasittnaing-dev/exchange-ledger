<?php

namespace App\Interfaces;

use App\Models\BankType;
use Illuminate\Database\Eloquent\Builder;

interface BankTypeRepositoryInterface
{
    public function getData(): Builder;

    public function createBankType(array $data): BankType;

    public function updateBankType(BankType $bankType, array $data): BankType;

    public function deleteBankType(BankType $bankType): void;
}
