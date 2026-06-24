<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\WorkPlanRepositoryInterface;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanOfficeExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class WorkPlanService
{
    public function __construct(
        private readonly WorkPlanRepositoryInterface $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function getEligibleMinorPlans(int $planMonth, int $planYear): Collection
    {
        return $this->repository->getEligibleMinorPlans($planMonth, $planYear);
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->createWorkPlan($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Work Plan created successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Work Plans Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function storeMajor(array $data): array
    {
        try {
            DB::beginTransaction();

            $this->repository->createMajorWorkPlan($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Major Work Plan created successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Major Work Plan Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during major plan creation.'
            ];
        }
    }

    public function update(WorkPlan $workPlan, array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->updateWorkPlan($workPlan, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Work Plan updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Work Plan Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function delete(WorkPlan $workPlan): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteWorkPlan($workPlan);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Work Plan deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Work Plan Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }
    public function storeExpense(array $data): array
    {
        try {
            DB::beginTransaction();
            $expense = $this->repository->createExpense($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Expense created successfully.',
                'data'    => $expense
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Expense Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }
    public function deleteOfficeExpense(WorkPlanOfficeExpense $workPlanOfficeExpense): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteOfficeExpense($workPlanOfficeExpense);
            return [
                'status' => true,
                'message' => 'Deleted Successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Office Expense Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during deletion.'
            ];
        }
    }
    public function updateOfficeExpense(array $data, WorkPlanOfficeExpense $workPlanOfficeExpense)
    {
        try {
            DB::beginTransaction();
            $this->repository->updateOfficeExpense($data, $workPlanOfficeExpense);
            DB::commit();


            return [
                'success' => true,
                'status'  => true,
                'message' => 'Updated Successfully.',
                'data'    =>
                [
                    'expense_category_id'   => $workPlanOfficeExpense->expense_category_id,
                    'expense_category_name' => $workPlanOfficeExpense->expenseCategory?->name ?? '',
                    'unit'                  => $workPlanOfficeExpense->unit,
                    'quantity'              => $workPlanOfficeExpense->quantity,
                    'unit_price'            => $workPlanOfficeExpense->unit_price,
                    'estimated_cost'        => $workPlanOfficeExpense->estimated_cost,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Office Expense Update Error: ");
            return [
                'success' => false,
                'status'  => false,
                'message' => 'Something went wrong during update.'
            ];
        }
    }
}
