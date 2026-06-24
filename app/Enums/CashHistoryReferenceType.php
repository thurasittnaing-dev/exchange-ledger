<?php

namespace App\Enums;

enum CashHistoryReferenceType: string
{
    case TRANSACTION = 'transaction';
    case OPENING = 'opening';
    case RESET = 'reset';

    public function label(): string
    {
        return match ($this) {
            self::TRANSACTION => 'Transaction',
            self::OPENING => 'Opening',
            self::RESET => 'Reset',
        };
    }

    public static function manualOptions(): array
    {
        return [
            self::OPENING->value => self::OPENING->label(),
            self::RESET->value => self::RESET->label(),
        ];
    }

    public static function manualValues(): array
    {
        return [
            self::OPENING->value,
            self::RESET->value,
        ];
    }
}
