<?php

namespace App\Models\Master;

use App\Models\Master\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $table = 'districts';

    protected $fillable = [
        'division_id',
        'name_en',
        'name_mm',
        'code',
    ];

    protected $guarded = [];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
