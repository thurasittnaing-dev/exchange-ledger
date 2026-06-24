<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\FeeType;
use App\Enums\TransactionType;
use InvalidArgumentException;

final class TransactionImpactCalculator
{
    /**
     * @return array{
     *     total_cash_impact: int,
     *     total_emoney_impact: int,
     *     fee_cash_profit: int,
     *     fee_emoney_profit: int
     * }
     */
    public static function compute(
        TransactionType $type,
        FeeType $feeType,
        int $amount,
        int $feeAmount
    ): array {
        if ($feeType === FeeType::NET && $feeAmount >= $amount) {
            throw new InvalidArgumentException('Fee amount must be less than transaction amount for net fee type.');
        }

        return match ($type) {
            TransactionType::CASH_IN => match ($feeType) {
                FeeType::EXACT => [
                    'total_cash_impact' => $amount + $feeAmount,
                    'total_emoney_impact' => $amount,
                    'fee_cash_profit' => $feeAmount,
                    'fee_emoney_profit' => 0,
                ],
                FeeType::NET => [
                    'total_cash_impact' => $amount,
                    'total_emoney_impact' => $amount - $feeAmount,
                    'fee_cash_profit' => 0,
                    'fee_emoney_profit' => $feeAmount,
                ],
            },
            TransactionType::CASH_OUT => match ($feeType) {
                FeeType::EXACT => [
                    'total_cash_impact' => $amount,
                    'total_emoney_impact' => $amount + $feeAmount,
                    'fee_cash_profit' => 0,
                    'fee_emoney_profit' => $feeAmount,
                ],
                FeeType::NET => [
                    'total_cash_impact' => $amount - $feeAmount,
                    'total_emoney_impact' => $amount,
                    'fee_cash_profit' => $feeAmount,
                    'fee_emoney_profit' => 0,
                ],
            },
        };
    }
}
