<?php

namespace App\Enums;

enum Provider: string
{
    case KBZ = 'kbz';
    case AYA = 'aya';
    case WAVEMONEY = 'wavemoney';
    case UAB = 'uab';
    case OKDOLLAR = 'okdollar';
    case TRUSTY = 'trusty';
    case YOMA = 'yoma';
    case CB = 'cb';
    case MAB = 'mab';
    case ABANK = 'abank';
    case APLUS = 'aplus';
    case SHWEBANKING = 'shwebanking';
    case MTBBANKING = 'mtb';
    case TRUEMONEY = 'truemoney';
    case MCB = 'mcb';
    case AGD = 'agd';
    case CASH = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::KBZ => 'KBZ',
            self::CB => 'CB',
            self::AYA => 'AYA',
            self::WAVEMONEY => 'WAVEMONEY',
            self::OKDOLLAR => 'Ok Dollar',
            self::TRUSTY => 'Trusty',
            self::UAB => 'UAB',
            self::AGD => 'AGD',
            self::YOMA => 'YOMA',
            self::ABANK => 'A Bank',
            self::APLUS => 'A Plus',
            self::SHWEBANKING => 'Shwe Banking',
            self::MTBBANKING => 'MTB Banking',
            self::TRUEMONEY => 'True Money',
            self::MCB => 'MCB Banking',
            self::MAB => 'MAB',
            self::CASH => 'Cash',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($role) => [
                $role->value => $role->label()
            ])
            ->toArray();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
