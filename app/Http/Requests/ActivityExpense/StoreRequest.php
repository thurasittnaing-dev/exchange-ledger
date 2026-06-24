<?php

namespace App\Http\Requests\ActivityExpense;

class StoreRequest extends ActivityExpenseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $activityGroup = $this->route('activityGroup');

            if (!$activityGroup) {
                return;
            }

            $this->validateSubActivityGroup($validator, $activityGroup, true);
        });
    }
}
