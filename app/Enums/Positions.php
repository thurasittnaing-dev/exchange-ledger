<?php

namespace App\Enums;

enum Positions: string
{
    case ONE = 'ညွှန်ကြားရေးမှုး';
    case TWO = 'ဦးစီးအရာရှိ';
    case THREE = 'ဒုတိယညွှန်ကြားရေးမှုး';
    case FOUR = 'လက်ထောက်ညွှန်ကြားရေးမှုး';
    case FIVE = 'ဌာနခွဲစာရေး';
    case SIX = 'တိုင်းဦးစီးမှုး';
    case SEVEN = 'ဒု-ကြီးကြပ်ရေးမှုး';
    case EIGHT = 'အကြီးတန်းစာရေး';
    case NINE = 'ပြည်နယ်ဦးစီးမှုး';

    public function label(): string
    {
        return match ($this) {
            self::ONE => 'ညွှန်ကြားရေးမှုး',
            self::TWO => 'ဦးစီးအရာရှိ',
            self::THREE => 'ဒုတိယညွှန်ကြားရေးမှုး',
            self::FOUR => 'လက်ထောက်ညွှန်ကြားရေးမှုး',
            self::FIVE => 'ဌာနခွဲစာရေး',
            self::SIX => 'တိုင်းဦးစီးမှုး',
            self::SEVEN => 'ဒု-ကြီးကြပ်ရေးမှုး',
            self::EIGHT => 'အကြီးတန်းစာရေး',
            self::NINE => 'ပြည်နယ်ဦးစီးမှုး',
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
