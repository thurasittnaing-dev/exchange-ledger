<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\WorkPlanActionRepositoryInterface;
use App\Models\Master\ActivityGroup;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanExpense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class WorkPlanActionService
{
    public function __construct(
        private readonly WorkPlanActionRepositoryInterface $repository
    ) {}

    public function takeAction(WorkPlan $workPlan): array
    {
        try {
            $this->repository->takeAction($workPlan);
            return [
                'status' => true,
                'message' => 'Take Action Successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Take Action Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function comment(WorkPlan $workPlan, array $data): array
    {
        try {

            DB::beginTransaction();

            $this->repository->comment($workPlan, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Comment Successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Comment Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function incomplete(WorkPlan $workPlan, array $data): array
    {
        try {

            DB::beginTransaction();

            $this->repository->incomplete($workPlan, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => "Case No : $workPlan->case_no is Incompleted."
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Incomplete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function resubmit(WorkPlan $workPlan, array $data): array
    {
        try {

            DB::beginTransaction();

            $this->repository->resubmit($workPlan, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => "Resubmited Success."
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Resubmit Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function approve(WorkPlan $workPlan, array $data): array
    {
        try {

            DB::beginTransaction();

            $this->repository->approve($workPlan, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => "Case No : $workPlan->case_no is Approved."
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Approved Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function addActivityGroupExpense(
        WorkPlan $workPlan,
        ActivityGroup $activityGroup,
        string $week,
        array $data
    ): array {
        try {

            DB::beginTransaction();

            $this->repository->addActivityGroupExpense(
                $workPlan,
                $activityGroup,
                $week,
                $data
            );

            DB::commit();
            return [
                'status' => true,
                'message' => 'Add Expense Successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Add Expense: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function updateActivityGroupExpense(
        WorkPlanExpense $workPlanExpense,
        array $data
    ): array {
        try {

            DB::beginTransaction();

            $this->repository->updateActivityGroupExpense(
                $workPlanExpense,
                $data
            );

            DB::commit();
            return [
                'status' => true,
                'message' => 'Update Successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Activity Expense Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function deleteActivityGroupExpense(WorkPlanExpense $workPlanExpense): array
    {
        try {
            $this->repository->deleteActivityGroupExpense($workPlanExpense);
            return [
                'status' => true,
                'message' => 'Deleted Successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Activity Expense Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }
}
