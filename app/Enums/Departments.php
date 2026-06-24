<?php

namespace App\Enums;

enum Departments: string
{
    case FOOD = 'food';
    case DRUG = 'drug';
    case COSMETIC = 'cosmetic';
    case MEDICAL_DEVICE = 'mdevice';

    public function label(): string
    {
        return match ($this) {
            self::FOOD => 'အစားအသောက်',
            self::DRUG => 'ဆေးဝါး',
            self::COSMETIC => 'အလှကုန်',
            self::MEDICAL_DEVICE => 'ဆေးပစ္စည်း',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [
                $status->value => $status->label()
            ])
            ->toArray();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
