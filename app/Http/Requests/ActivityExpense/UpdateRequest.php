<?php

namespace App\Http\Requests\ActivityExpense;

class UpdateRequest extends ActivityExpenseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $workPlanExpense = $this->route('workPlanExpense');
            $activityGroup = $workPlanExpense?->activityGroup;

            if (!$activityGroup) {
                return;
            }

            $this->validateSubActivityGroup($validator, $activityGroup, true);
        });
    }
}
