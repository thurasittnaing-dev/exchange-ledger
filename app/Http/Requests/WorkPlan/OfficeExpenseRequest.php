<?php

namespace App\Http\Requests\WorkPlan;

use Illuminate\Foundation\Http\FormRequest;

class OfficeExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expense_category_id' => 'required',
            'quantity' => 'nullable|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'estimated_cost' => 'required|numeric|min:0',
            'work_plan_id'        => 'required|numeric',
            'unit' => 'nullable|string'
        ];
    }
    public function messages(): array
    {
        return [
            'quantity.min' => 'Quantity cannot be a negative value.',
            'unit_price.min' => 'Unit price must be greater than 0',
            'estimated_cost.min' => 'Estimated cost must be greater than 0.',
        ];
    }
}
