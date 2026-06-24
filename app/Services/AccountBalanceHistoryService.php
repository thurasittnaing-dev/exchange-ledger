<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AccountHistoryAction;
use App\Enums\AccountHistoryReferenceType;
use App\Interfaces\AccountBalanceHistoryRepositoryInterface;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class AccountBalanceHistoryService
{
    public function __construct(
        private readonly AccountBalanceHistoryRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getManualData();
    }

    public function store(array $data): array
    {
        try {
            DB::transaction(function () use ($data) {
                $account = Account::query()
                    ->lockForUpdate()
                    ->findOrFail($data['account_id']);

                $runningBalance = $account->balance;
                $amount = (int) $data['amount'];
                $description = $data['description'] ?? '';
                $referenceType = AccountHistoryReferenceType::from($data['reference_type']);

                if ($referenceType === AccountHistoryReferenceType::MANUAL_ADD) {
                    $account->update(['balance' => $runningBalance + $amount]);

                    $this->repository->createHistory([
                        'account_id' => $account->id,
                        'reference_type' => AccountHistoryReferenceType::MANUAL_ADD->value,
                        'reference_id' => null,
                        'action' => AccountHistoryAction::CREDIT->value,
                        'amount' => $amount,
                        'running_balance' => $runningBalance,
                        'description' => $description,
                    ]);

                    return;
                }

                $account->update(['balance' => $amount]);

                $this->repository->createHistory([
                    'account_id' => $account->id,
                    'reference_type' => AccountHistoryReferenceType::MANUAL_RESET->value,
                    'reference_id' => null,
                    'action' => AccountHistoryAction::RESET->value,
                    'amount' => $amount,
                    'running_balance' => $runningBalance,
                    'description' => $description,
                ]);
            });

            return [
                'status' => true,
                'message' => 'Account balance updated successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Account Balance Update Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during balance update.',
            ];
        }
    }
}
