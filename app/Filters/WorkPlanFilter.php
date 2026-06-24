<?php

declare(strict_types=1);

namespace App\Filters;

use App\Enums\NavStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class WorkPlanFilter
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $query->when($this->request->title, function ($q) {
            $q->where('title', 'like', '%' . $this->request->title . '%');
        });

        $query->when(
            $this->request->filled('type') && in_array($this->request->type, NavStatus::values(), true),
            fn($q) => $q->where('status', $this->request->type)
        );

        $query->when($this->request->task === 'my-tasks', function ($q) {
            $q->where('current_officer_id', auth()->id());
        });

        return $query;
    }
}
