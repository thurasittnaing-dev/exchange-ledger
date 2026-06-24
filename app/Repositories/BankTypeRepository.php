<?php

namespace App\Repositories;

use App\Interfaces\BankTypeRepositoryInterface;
use App\Models\BankType;
use Illuminate\Database\Eloquent\Builder;

class BankTypeRepository implements BankTypeRepositoryInterface
{
    public function getData(): Builder
    {
        return BankType::query();
    }

    public function createBankType(array $data): BankType
    {
        return BankType::create($data);
    }

    public function updateBankType(BankType $bankType, array $data): BankType
    {
        $bankType->update($data);

        return $bankType;
    }

    public function deleteBankType(BankType $bankType): void
    {
        $bankType->delete();
    }
}
