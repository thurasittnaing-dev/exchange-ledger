<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class PermissionFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        // Module
        $query->when($this->request->module, function ($q) {
            $q->where('module', 'like', '%' . $this->request->module . '%');
        });

        // Code
        $query->when($this->request->code, function ($q) {
            $q->where('code', 'like', '%' . $this->request->code . '%');
        });

        // Name
        $query->when($this->request->name, function ($q) {
            $q->where('name', 'like', '%' . $this->request->name . '%');
        });

        return $query;
    }
}
