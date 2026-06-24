<?php

namespace App\Http\Requests\ActivityExpense;

use App\Models\Master\ActivityGroup;
use Illuminate\Foundation\Http\FormRequest;

abstract class ActivityExpenseRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sub_activity_group_id' => ['nullable', 'integer', 'exists:activity_groups,id'],
            'substance_id' => ['nullable', 'exists:substances,id'],
            'description' => ['required', 'string'],
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.expense_category_id' => ['required', 'exists:expense_categories,id'],
            'expenses.*.estimate_cost' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'substance_id.exists' => 'The selected work type is invalid.',
            'description.required' => 'Please enter a work plan description.',
            'expenses.required' => 'Please add at least one expense item.',
            'expenses.min' => 'Please add at least one expense item.',
            'expenses.*.expense_category_id.required' => 'Please select an expense category.',
            'expenses.*.expense_category_id.exists' => 'The selected expense category is invalid.',
            'expenses.*.estimate_cost.required' => 'Please enter an estimate cost.',
            'expenses.*.estimate_cost.numeric' => 'Estimate cost must be a valid number.',
            'expenses.*.estimate_cost.min' => 'Estimate cost cannot be negative.',
            'sub_activity_group_id.exists' => 'The selected sub category is invalid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'sub_activity_group_id' => 'sub category',
            'substance_id' => 'work type',
            'description' => 'work plan description',
            'expenses' => 'expense items',
            'expenses.*.expense_category_id' => 'expense category',
            'expenses.*.estimate_cost' => 'estimate cost',
        ];
    }

    protected function validateSubActivityGroup($validator, ActivityGroup $activityGroup, bool $required): void
    {
        $hasChildren = $activityGroup->children()->where('is_active', true)->exists();
        $subActivityGroupId = $this->input('sub_activity_group_id');

        if ($hasChildren && $required && empty($subActivityGroupId)) {
            $validator->errors()->add(
                'sub_activity_group_id',
                'Please select a sub category for this activity group.'
            );

            return;
        }

        if (empty($subActivityGroupId)) {
            return;
        }

        $subActivityGroup = ActivityGroup::query()
            ->where('id', (int) $subActivityGroupId)
            ->where('is_active', true)
            ->first();

        if (!$subActivityGroup || !$subActivityGroup->isDescendantOf($activityGroup)) {
            $validator->errors()->add(
                'sub_activity_group_id',
                'The selected sub category is invalid.'
            );

            return;
        }

        if ($subActivityGroup->children()->where('is_active', true)->exists()) {
            $validator->errors()->add(
                'sub_activity_group_id',
                'Please select a sub category down to the lowest level.'
            );
        }
    }
}
