<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'bank_type_id',
        'balance',
    ];

    public function bankType(): BelongsTo
    {
        return $this->belongsTo(BankType::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AccountHistory::class);
    }
}
