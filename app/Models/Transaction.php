<?php

namespace App\Models;

use App\Enums\FeeType;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'fee_amount',
        'fee_type',
        'total_cash_impact',
        'total_emoney_impact',
        'fee_cash_profit',
        'fee_emoney_profit',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'fee_type' => FeeType::class,
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
