<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class UserFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        // Full Name
        $query->when($this->request->name, function ($q) {
            $q->where('name', 'like', '%' . $this->request->name . '%');
        });

        // Username
        $query->when($this->request->username, function ($q) {
            $q->where('username', 'like', '%' . $this->request->username . '%');
        });

        return $query;
    }
}
