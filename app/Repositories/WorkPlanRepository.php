<?php

namespace App\Repositories;

use App\Enums\PlanStatus;
use App\Interfaces\WorkPlanRepositoryInterface;
use App\Models\Plan\WorkPlan;
use App\Models\Plan\WorkPlanOfficeExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkPlanRepository implements WorkPlanRepositoryInterface
{
    public function getData(): Builder
    {
        $user = auth()->user();
        return WorkPlan::query()
            ->with(['division', 'district'])
            ->when($user->is_staff, fn($q) => $q->where('division_id', $user->division_id))
            ->when(!$user->is_staff, fn($q) => $q->where('type', 'major'))
            ->when(!$user->is_region, fn ($q) => $q
            ->where(fn ($query) => $query->whereNull('parent_id')
            ->orWhere(fn ($subQuery) => $subQuery->where('status', 'incomplete')->whereNotNull('parent_id'))));
    }

    public function getEligibleMinorPlans(int $planMonth, int $planYear): Collection
    {
        $user = auth()->user();

        return WorkPlan::query()
            ->with('district:id,name_en')
            ->where('type', 'minor')
            ->whereNull('parent_id')
            ->where('division_id', $user->division_id)
            ->where('plan_month', $planMonth)
            ->where('plan_year', $planYear)
            ->orderByDesc('submitted_at')
            ->get(['id', 'case_no', 'title', 'plan_month', 'plan_year', 'district_id', 'status', 'submitted_at']);
    }

    public function createWorkPlan(array $data): WorkPlan
    {
        $user = auth()->user();
        $dateParts = explode('-', $data['plan_month'] ?? '');
        $data['plan_month'] =  isset($dateParts[0]) ? (int) $dateParts[0] : null;
        $data['plan_year'] = isset($dateParts[1]) ? (int) $dateParts[1] : null;
        $data['division_id'] = $user?->division_id;
        $data['district_id'] = $user?->district_id;
        $data['creator_id'] = $user?->id;
        $data['type'] = 'minor';
        $data['submitted_at'] = now();
        $data['status'] = (string) PlanStatus::SUBMITTED->value;

        return WorkPlan::create($data);
    }

    public function createMajorWorkPlan(array $data): WorkPlan
    {
        $user = auth()->user();
        $dateParts = explode('-', $data['plan_month'] ?? '');
        $planMonth = isset($dateParts[0]) ? (int) $dateParts[0] : null;
        $planYear = isset($dateParts[1]) ? (int) $dateParts[1] : null;
        $minorPlanIds = $data['minor_plan_ids'] ?? [];

        $major = WorkPlan::create([
            'title' => $data['title'],
            'plan_month' => $planMonth,
            'plan_year' => $planYear,
            'division_id' => $user->division_id,
            'creator_id' => $user->id,
            'type' => 'major',
            'submitted_at' => now(),
            'status' => (string) PlanStatus::SUBMITTED->value,
        ]);

        WorkPlan::query()
            ->whereIn('id', $minorPlanIds)
            ->where('type', 'minor')
            ->whereNull('parent_id')
            ->where('division_id', $user->division_id)
            ->where('plan_month', $planMonth)
            ->where('plan_year', $planYear)
            ->update(['parent_id' => $major->id]);

        return $major;
    }

    public function updateWorkPlan(WorkPlan $workPlan, array $data): WorkPlan
    {
        if (isset($data['plan_month'])) {
            $dateParts = explode('-', $data['plan_month']);
            $data['plan_month'] = isset($dateParts[0]) ? (int) $dateParts[0] : null;
            $data['plan_year'] = isset($dateParts[1]) ? (int) $dateParts[1] : null;
        }

        $workPlan->update($data);

        return $workPlan;
    }
    public function deleteWorkPlan(WorkPlan $workPlan): void
    {
        $workPlan->delete();
    }
    public function createExpense(array $data): WorkPlanOfficeExpense
    {
        return WorkPlanOfficeExpense::create($data);
    }
    public function deleteOfficeExpense(WorkPlanOfficeExpense $workPlanOfficeExpense): void
    {
        $workPlanOfficeExpense->delete();
    }
    public function updateOfficeExpense(array $data, WorkPlanOfficeExpense $workPlanOfficeExpense): WorkPlanOfficeExpense
    {
        $workPlanOfficeExpense->update($data);
        return $workPlanOfficeExpense;
    }
}
