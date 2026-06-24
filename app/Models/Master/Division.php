<?php

namespace App\Models\Master;

use App\Models\District;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $table = 'divisions';

    protected $fillable = [
        'name_en',
        'name_mm',
        'code',
        'level',
    ];

    protected $guarded = [];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
