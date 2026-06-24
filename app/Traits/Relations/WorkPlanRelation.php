<?php

namespace App\Traits\Relations;

use App\Models\Master\District;
use App\Models\Master\Division;
use App\Models\Master\ActivityGroup;
use App\Models\Plan\WorkPlanAction;
use App\Models\Plan\WorkPlanComment;
use App\Models\Plan\WorkPlanExpense;
use App\Models\Management\User;
use App\Models\Plan\WorkPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait  WorkPlanRelation
{
    public function major(): BelongsTo
    {
        return $this->belongsTo(WorkPlan::class, 'parent_id', 'id');
    }

    public function minors(): HasMany
    {
        return $this->hasMany(WorkPlan::class, 'parent_id', 'id');
    }

    public function division(): BelongsTo
    {
        return $this->BelongsTo(Division::class, 'division_id');
    }

    public function district(): BelongsTo
    {
        return $this->BelongsTo(District::class, 'district_id');
    }

    public function currentOfficer(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'current_officer_id');
    }

    public function approveOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approve_officer_id');
    }

    public function expenses(): HasMany
    {
        return $this->HasMany(WorkPlanExpense::class, 'work_plan_id');
    }

    public function actions(): HasMany
    {
        return $this->HasMany(WorkPlanAction::class, 'work_plan_id');
    }

    public function comments(): HasMany
    {
        return $this->HasMany(WorkPlanComment::class, 'work_plan_id');
    }

    public function activityGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            ActivityGroup::class,
            'work_plan_activity_groups',
            'work_plan_id',
            'activity_group_id'
        )->withTimestamps();
    }
}
