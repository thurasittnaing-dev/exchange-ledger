<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ExpenseCategoryFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        // Name
        $query->when($this->request->name, function ($q) {
            $q->where('name', 'like', '%' . $this->request->name . '%');
        });

        // Code
        $query->when($this->request->code, function ($q) {
            $q->where('code', $this->request->code);
        });
        // Type
        $query->when($this->request->type, function ($q) {
            $q->where('type', $this->request->type);
        });
        return $query;
    }
}
