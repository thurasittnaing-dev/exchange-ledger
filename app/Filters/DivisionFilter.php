<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class DivisionFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        // Name Eng
        $query->when($this->request->name_en, function ($q) {
            $q->where('name_en', 'like', '%' . $this->request->name_en . '%');
        });

        // Name MM
        $query->when($this->request->name_mm, function ($q) {
            $q->where('name_mm', 'like', '%' . $this->request->name_mm . '%');
        });

        // Code
        $query->when($this->request->code, function ($q) {
            $q->where('code', $this->request->code);
        });

        return $query;
    }
}
