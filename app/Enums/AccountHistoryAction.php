<?php

namespace App\Enums;

enum AccountHistoryAction: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
    case RESET = 'reset';

    public function label(): string
    {
        return match ($this) {
            self::CREDIT => 'Credit',
            self::DEBIT => 'Debit',
            self::RESET => 'Reset',
        };
    }
}
