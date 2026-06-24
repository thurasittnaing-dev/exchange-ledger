<?php

namespace App\Interfaces;

use App\Models\Master\ActivityGroup;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanExpense;

interface WorkPlanActionRepositoryInterface
{
    public function takeAction(WorkPlan $workPlan): void;

    public function comment(WorkPlan $workPlan, array $data): void;

    public function incomplete(WorkPlan $workPlan, array $data): void;

    public function resubmit(WorkPlan $workPlan, array $data): void;

    public function approve(WorkPlan $workPlan, array $data): void;

    public function addOfficeExpense(): void;

    public function addCommonExpense(): void;

    public function addActivityGroupExpense(
        WorkPlan $workPlan,
        ActivityGroup $activityGroup,
        string $week,
        array $data
    ): void;

    public function updateActivityGroupExpense(
        WorkPlanExpense $workPlanActivityExpense,
        array $data
    ): void;

    public function deleteActivityGroupExpense(
        WorkPlanExpense $workPlanActivityExpense,
    ): void;
}
