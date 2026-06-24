<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class SubstanceFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $query->when($this->request->name, function ($q) {
            $q->where('name', 'like', '%' . $this->request->name . '%');
        });

        return $query;
    }
}
