<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

final class AccountService
{
    public function __construct(
        private readonly AccountRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            $data['balance'] = $data['balance'] ?? 0;
            $this->repository->createAccount($data);

            return [
                'status' => true,
                'message' => 'Account created successfully',
            ];
        } catch (\Exception $e) {
            showError($e, 'Account Create Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Account creation',
            ];
        }
    }

    public function update(Account $account, array $data): array
    {
        try {
            $this->repository->updateAccount($account, $data);

            return [
                'status' => true,
                'message' => 'Account updated successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Account Update Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Account update.',
            ];
        }
    }

    public function delete(Account $account): array
    {
        try {
            $this->repository->deleteAccount($account);

            return [
                'status' => true,
                'message' => 'Account deleted successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Account Delete Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during Account delete.',
            ];
        }
    }
}
