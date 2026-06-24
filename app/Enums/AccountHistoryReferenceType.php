<?php

namespace App\Enums;

enum AccountHistoryReferenceType: string
{
    case TRANSACTION = 'transaction';
    case CASH_HISTORY = 'cash_history';
    case MANUAL_ADD = 'manual_add';
    case MANUAL_RESET = 'manual_reset';

    public function label(): string
    {
        return match ($this) {
            self::TRANSACTION => 'Transaction',
            self::CASH_HISTORY => 'Cash History',
            self::MANUAL_ADD => 'Add',
            self::MANUAL_RESET => 'Reset',
        };
    }

    public static function manualOptions(): array
    {
        return [
            self::MANUAL_ADD->value => self::MANUAL_ADD->label(),
            self::MANUAL_RESET->value => self::MANUAL_RESET->label(),
        ];
    }

    public static function manualValues(): array
    {
        return [
            self::MANUAL_ADD->value,
            self::MANUAL_RESET->value,
        ];
    }
}
