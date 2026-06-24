<?php

namespace App\Repositories;

use App\Enums\ActionType;
use App\Interfaces\WorkPlanActionRepositoryInterface;
use App\Models\Master\ActivityGroup;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanAction;
use App\Models\Plan\WorkPlanComment;
use App\Models\Plan\WorkPlanExpense;
use App\Templates\WorkPlan\ApproveLetter;

class WorkPlanActionRepository implements WorkPlanActionRepositoryInterface
{
    public function takeAction(WorkPlan $workPlan): void
    {
        $workPlan->update([
            'current_officer_id' => auth()->user()?->id
        ]);
    }

    public function comment(WorkPlan $workPlan, array $data): void
    {
        $workPlan->update([
            'current_officer_id' => $data['officer_id']
        ]);

        WorkPlanComment::create([
            'work_plan_id' => $workPlan->id,
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  $data['officer_id'],
            'comment' => $data['description'],
        ]);
    }

    public function incomplete(WorkPlan $workPlan, array $data): void
    {
        // store selected ids in session
        if(isset($data['minor_plan_ids']))
            {
                session(['selected_minor_plan_ids' => $data['minor_plan_ids'] ?? []]);

                $minorWorkPlans = WorkPlan::whereIn('id', $data['minor_plan_ids'])->get();
                foreach($minorWorkPlans as $minorWorkPlan)
                {
                    $minorWorkPlan->update([
                        'status' => ActionType::INCOMPLETE->value,
                        // 'current_officer_id' => $minorWorkPlan->creator_id
                    ]);
                }
                session()->forget('selected_minor_plan_ids');

            }

        $lastComment = $workPlan->comments()->latest()->first();

        $workPlan->update([
            'status' => ActionType::INCOMPLETE->value,
            'current_officer_id' => $lastComment->from_officer_id,
        ]);

        WorkPlanAction::create([
            'work_plan_id' => $workPlan->id,
            'action_type' => ActionType::INCOMPLETE->value,
            'action_date' => now(),
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  $lastComment->from_officer_id,
            'comment' => $data['description'],
        ]);

        WorkPlanComment::create([
            'work_plan_id' => $workPlan->id,
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  $lastComment->from_officer_id,
            'comment' => $data['description'],
            'type' => 'Incomplete',
        ]);
    }

    public function resubmit(WorkPlan $workPlan, array $data): void
    {
        $lastComment = $workPlan->comments()->latest()->first();
        $status = $workPlan->type == 'major' ? ActionType::RESUBMITTED->value : ActionType::SUBMITTED->value;

        $workPlan->update([
            'status' => $status,
            'current_officer_id' => $lastComment->from_officer_id,
        ]);

        WorkPlanAction::create([
            'work_plan_id' => $workPlan->id,
            'action_type' => $status,
            'action_date' => now(),
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  $lastComment->from_officer_id,
            'comment' => $data['description'],
        ]);

        WorkPlanComment::create([
            'work_plan_id' => $workPlan->id,
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  $lastComment->from_officer_id,
            'comment' => $data['description'],
            'type' => 'Resubmit',
        ]);
    }

    public function approve(WorkPlan $workPlan, array $data): void
    {
        $workPlan->update([
            'status' => ActionType::APPROVE->value,
            'approved_at' => now(),
            'approve_officer_id' => auth()->user()?->id,
            'current_officer_id' => null,
        ]);

        WorkPlanAction::create([
            'work_plan_id' => $workPlan->id,
            'action_type' => ActionType::APPROVE->value,
            'action_date' => now(),
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  auth()->user()?->id,
            'comment' => $data['description'],
        ]);

        WorkPlanComment::create([
            'work_plan_id' => $workPlan->id,
            'from_officer_id' => auth()->user()?->id,
            'to_officer_id' =>  auth()->user()?->id,
            'comment' => $data['description'],
            'type' => 'Approve',
        ]);

    }

    public function addActivityGroupExpense(
        WorkPlan $workPlan,
        ActivityGroup $activityGroup,
        string $week,
        array $data
    ): void {
        $subActivityGroupId = $data['sub_activity_group_id'] ?? null;

        $activityExpense = WorkPlanExpense::create([
            'work_plan_id' => $workPlan->id,
            'activity_group_id' => $activityGroup->id,
            'sub_activity_group_id' => $subActivityGroupId,
            'substance_id' => $data['substance_id'] ?? null,
            'week' => $week,
            'content' => $data['description'],
        ]);

        if ($subActivityGroupId) {
            $workPlan->activityGroups()->syncWithoutDetaching([(int) $subActivityGroupId]);
        }

        $activityExpense->expenseItems()->createMany($data['expenses']);
    }

    public function updateActivityGroupExpense(
        WorkPlanExpense $workPlanActivityExpense,
        array $data
    ): void {
        $subActivityGroupId = $data['sub_activity_group_id'] ?? null;

        $workPlanActivityExpense->update([
            'sub_activity_group_id' => $subActivityGroupId,
            'substance_id' => $data['substance_id'] ?? null,
            'content' => $data['description'],
        ]);

        if ($subActivityGroupId) {
            $workPlanActivityExpense->workPlan?->activityGroups()->syncWithoutDetaching([(int) $subActivityGroupId]);
        }

        $workPlanActivityExpense->expenseItems()->delete();
        $workPlanActivityExpense->expenseItems()->createMany($data['expenses']);
    }

    public function deleteActivityGroupExpense(
        WorkPlanExpense $workPlanActivityExpense
    ): void {
        $workPlanActivityExpense->delete();
    }


    public function addOfficeExpense(): void {}

    public function addCommonExpense(): void {}
}
