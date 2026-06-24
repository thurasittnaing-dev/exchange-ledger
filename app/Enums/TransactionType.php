<?php

namespace App\Enums;

enum TransactionType: string
{
    case CASH_IN = 'cash_in';
    case CASH_OUT = 'cash_out';

    public function label(): string
    {
        return match ($this) {
            self::CASH_IN => 'Cash-In',
            self::CASH_OUT => 'Cash-Out',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
