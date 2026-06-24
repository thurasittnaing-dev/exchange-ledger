<?php

namespace App\Enums;

enum FeeType: string
{
    case EXACT = 'exact';
    case NET = 'net';

    public function label(): string
    {
        return match ($this) {
            self::EXACT => 'Exact Top-up',
            self::NET => 'Net Top-up',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
