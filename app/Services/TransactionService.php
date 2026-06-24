<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AccountHistoryAction;
use App\Enums\AccountHistoryReferenceType;
use App\Enums\CashHistoryAction;
use App\Enums\CashHistoryReferenceType;
use App\Enums\FeeType;
use App\Enums\TransactionType;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Account;
use App\Models\AccountHistory;
use App\Models\CashBalance;
use App\Models\CashHistory;
use App\Support\TransactionImpactCalculator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function emoneyTotal(): int
    {
        return (int) Account::query()->sum('balance');
    }

    public function cashTotal(): int
    {
        return CashBalance::amount();
    }

    public function summary(Builder $query): array
    {
        $feeCash = (int) (clone $query)->sum('fee_cash_profit');
        $feeEmoney = (int) (clone $query)->sum('fee_emoney_profit');

        return [
            'fee_cash_profit' => $feeCash,
            'fee_emoney_profit' => $feeEmoney,
            'total_profit' => $feeCash + $feeEmoney,
        ];
    }

    public function store(array $data): array
    {
        try {
            DB::transaction(function () use ($data) {
                $type = TransactionType::from($data['type']);
                $feeType = FeeType::from($data['fee_type']);
                $amount = (int) $data['amount'];
                $feeAmount = (int) $data['fee_amount'];
                $description = $data['description'] ?? '';

                $impacts = TransactionImpactCalculator::compute($type, $feeType, $amount, $feeAmount);

                $account = Account::query()->lockForUpdate()->findOrFail($data['account_id']);
                $cashBalance = CashBalance::query()->lockForUpdate()->firstOrCreate(['id' => 1], ['balance' => 0]);

                if ($type === TransactionType::CASH_IN && $account->balance < $impacts['total_emoney_impact']) {
                    throw new InvalidArgumentException('Insufficient emoney balance in selected account.');
                }

                if ($type === TransactionType::CASH_OUT && $cashBalance->balance < $impacts['total_cash_impact']) {
                    throw new InvalidArgumentException('Insufficient cash balance.');
                }

                $transaction = $this->repository->createTransaction([
                    'account_id' => $account->id,
                    'type' => $type->value,
                    'amount' => $amount,
                    'fee_amount' => $feeAmount,
                    'fee_type' => $feeType->value,
                    'total_cash_impact' => $impacts['total_cash_impact'],
                    'total_emoney_impact' => $impacts['total_emoney_impact'],
                    'fee_cash_profit' => $impacts['fee_cash_profit'],
                    'fee_emoney_profit' => $impacts['fee_emoney_profit'],
                    'description' => $description,
                ]);

                $accountRunning = (int) $account->balance;
                $cashRunning = (int) $cashBalance->balance;

                if ($type === TransactionType::CASH_IN) {
                    $account->update(['balance' => $accountRunning - $impacts['total_emoney_impact']]);
                    $cashBalance->update(['balance' => $cashRunning + $impacts['total_cash_impact']]);

                    AccountHistory::create([
                        'account_id' => $account->id,
                        'reference_type' => AccountHistoryReferenceType::TRANSACTION->value,
                        'reference_id' => $transaction->id,
                        'action' => AccountHistoryAction::DEBIT->value,
                        'amount' => $impacts['total_emoney_impact'],
                        'running_balance' => $accountRunning,
                        'description' => $description ?: 'Cash-In Transaction #' . $transaction->id,
                    ]);

                    CashHistory::create([
                        'reference_type' => CashHistoryReferenceType::TRANSACTION->value,
                        'reference_id' => $transaction->id,
                        'action' => CashHistoryAction::CREDIT->value,
                        'amount' => $impacts['total_cash_impact'],
                        'running_balance' => $cashRunning,
                        'description' => $description ?: 'Cash-In Transaction #' . $transaction->id,
                    ]);

                    return;
                }

                $account->update(['balance' => $accountRunning + $impacts['total_emoney_impact']]);
                $cashBalance->update(['balance' => $cashRunning - $impacts['total_cash_impact']]);

                AccountHistory::create([
                    'account_id' => $account->id,
                    'reference_type' => AccountHistoryReferenceType::TRANSACTION->value,
                    'reference_id' => $transaction->id,
                    'action' => AccountHistoryAction::CREDIT->value,
                    'amount' => $impacts['total_emoney_impact'],
                    'running_balance' => $accountRunning,
                    'description' => $description ?: 'Cash-Out Transaction #' . $transaction->id,
                ]);

                CashHistory::create([
                    'reference_type' => CashHistoryReferenceType::TRANSACTION->value,
                    'reference_id' => $transaction->id,
                    'action' => CashHistoryAction::DEBIT->value,
                    'amount' => $impacts['total_cash_impact'],
                    'running_balance' => $cashRunning,
                    'description' => $description ?: 'Cash-Out Transaction #' . $transaction->id,
                ]);
            });

            return [
                'status' => true,
                'message' => 'Transaction recorded successfully.',
            ];
        } catch (InvalidArgumentException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            showError($e, 'Transaction Create Error: ');

            return [
                'status' => false,
                'message' => 'Something went wrong during transaction creation.',
            ];
        }
    }
}
