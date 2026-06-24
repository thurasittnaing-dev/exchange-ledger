<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBalance extends Model
{
    protected $fillable = [
        'balance',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1], ['balance' => 0]);
    }

    public static function amount(): int
    {
        return (int) static::current()->balance;
    }
}
