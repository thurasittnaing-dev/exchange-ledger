<?php

namespace App\Http\Requests\WorkPlan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMajorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'plan_month' => ['required'],
            'minor_plan_ids' => ['required', 'array', 'min:1'],
            'minor_plan_ids.*' => ['required', 'integer', 'exists:work_plans,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'minor_plan_ids.required' => 'Please select at least one minor plan.',
            'minor_plan_ids.min' => 'Please select at least one minor plan.',
        ];
    }
}
