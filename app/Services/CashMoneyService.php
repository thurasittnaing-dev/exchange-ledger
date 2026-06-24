<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CashHistoryAction;
use App\Enums\CashHistoryReferenceType;
use App\Interfaces\CashMoneyRepositoryInterface;
use App\Models\CashBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class CashMoneyService
{
    public function __construct(
        private readonly CashMoneyRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getManualData();
    }

    public function currentBalance(): int
    {
        return CashBalance::amount();
    }

    public function store(array $data): array
    {
        try {
            DB::transaction(function () use ($data) {
                $cashBalance = CashBalance::query()->lockForUpdate()->firstOrCreate(['id' => 1], ['balance' => 0]);
                $runningBalance = (int) $cashBalance->balance;
                $amount = (int) $data['amount'];
                $description = $data['description'] ?? '';
                $referenceType = CashHistoryReferenceType::from($data['reference_type']);

                if ($referenceType === CashHistoryReferenceType::OPENING) {
                    $cashBalance->update(['balance' => $runningBalance + $amount]);

                    $this->repository->createHistory([
                        'reference_type' => CashHistoryReferenceType::OPENING->value,
                        'reference_id' => null,
                        'action' => CashHistoryAction::CREDIT->value,
                        'amount' => $amount,
                        'running_balance' => $runningBalance,
                        'description' => $description,
                    ]);

                    return;
                }

                $cashBalance->update(['balance' => $amount]);

                $this->repository->createHistory([
                    'reference_type' => CashHistoryReferenceType::RESET->value,
                    'reference_id' => null,
                    'action' => CashHistoryAction::RESET->value,
                    'amount' => $amount,
                    'running_balance' => $runningBalance,
                    'description' => $description,
                ]);
            });

            return [
                'status' => true,
                'message' => 'Cash balance updated successfully.',
            ];
        } catch (\Exception $e) {
            showError($e, 'Cash Balance Update Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during cash balance update.',
            ];
        }
    }
}
