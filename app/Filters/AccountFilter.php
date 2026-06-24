<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class AccountFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $query->when($this->request->name, function ($q) {
            $q->where('name', 'like', '%' . $this->request->name . '%');
        });

        $query->when($this->request->bank_type_id, function ($q) {
            $q->where('bank_type_id', $this->request->bank_type_id);
        });

        $query->when($this->request->provider, function ($q) {
            $q->whereHas('bankType', function ($q) {
                $q->where('provider', $this->request->provider);
            });
        });

        return $query;
    }
}
