<?php

namespace App\Interfaces;

use App\Http\Requests\WorkPlan\OfficeExpenseRequest;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanOfficeExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface WorkPlanRepositoryInterface
{
    public function getData(): Builder;
    public function getEligibleMinorPlans(int $planMonth, int $planYear): Collection;
    public function createWorkPlan(array $data): WorkPlan;
    public function createMajorWorkPlan(array $data): WorkPlan;
    public function updateWorkPlan(WorkPlan $workPlan, array $data): WorkPlan;
    public function deleteWorkPlan(WorkPlan $workPlan): void;
    public function createExpense(array $data): WorkPlanOfficeExpense;
    public function deleteOfficeExpense(WorkPlanOfficeExpense $workPlanOfficeExpense): void;
    public function updateOfficeExpense(array $data, WorkPlanOfficeExpense $workPlanOfficeExpense) : WorkPlanOfficeExpense;
}
