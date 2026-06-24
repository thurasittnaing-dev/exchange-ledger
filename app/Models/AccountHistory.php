<?php

namespace App\Models;

use App\Enums\AccountHistoryAction;
use App\Enums\AccountHistoryReferenceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountHistory extends Model
{
    protected $fillable = [
        'account_id',
        'reference_type',
        'reference_id',
        'action',
        'amount',
        'running_balance',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'reference_type' => AccountHistoryReferenceType::class,
            'action' => AccountHistoryAction::class,
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
