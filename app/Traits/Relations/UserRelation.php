<?php

namespace App\Traits\Relations;

use App\Models\Master\District;
use App\Models\Master\Division;
use App\Models\Management\Document;
use App\Models\Management\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait  UserRelation
{

    public function division(): BelongsTo
    {
        return $this->BelongsTo(Division::class, 'division_id');
    }

    public function district(): BelongsTo
    {
        return $this->BelongsTo(District::class, 'district_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function profileDocument(): BelongsTo
    {
        return $this->BelongsTo(Document::class, 'profile_image', 'uuid');
    }
}
