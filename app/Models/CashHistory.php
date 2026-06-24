<?php

namespace App\Models;

use App\Enums\CashHistoryAction;
use App\Enums\CashHistoryReferenceType;
use Illuminate\Database\Eloquent\Model;

class CashHistory extends Model
{
    protected $fillable = [
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
            'reference_type' => CashHistoryReferenceType::class,
            'action' => CashHistoryAction::class,
        ];
    }
}
