<?php

namespace App\Models;

use App\Enums\Provider;
use Illuminate\Database\Eloquent\Model;

class BankType extends Model
{
    protected $table = 'bank_types';

    protected $fillable = [
        'name',
        'provider',
    ];
}
