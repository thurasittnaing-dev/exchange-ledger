<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BankTypeRepositoryInterface;
use App\Models\BankType;
use Illuminate\Database\Eloquent\Builder;

final class BankTypeService
{
    public function __construct(
        private readonly BankTypeRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            $this->repository->createBankType($data);

            return [
                'status' => true,
                'message' => 'Bank Type created successfully',
            ];
        } catch (\Exception $e) {
            showError($e, 'Bank Type Create Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Bank Type creation',
            ];
        }
    }

    public function update(BankType $bankType, array $data): array
    {
        try {
            $this->repository->updateBankType($bankType, $data);

            return [
                'status' => true,
                'message' => 'Bank Type updated successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Bank Type Update Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Bank Type update.',
            ];
        }
    }

    public function delete(BankType $bankType): array
    {
        try {
            $this->repository->deleteBankType($bankType);

            return [
                'status' => true,
                'message' => 'Bank Type deleted successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Bank Type Delete Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Bank Type delete.',
            ];
        }
    }
}
