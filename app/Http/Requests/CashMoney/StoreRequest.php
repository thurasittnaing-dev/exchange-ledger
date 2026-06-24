<?php

namespace App\Http\Requests\CashMoney;

use App\Enums\CashHistoryReferenceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'reference_type' => ['required', 'string', Rule::in(CashHistoryReferenceType::manualValues())],
            'amount' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->reference_type === CashHistoryReferenceType::OPENING->value && (int) $this->amount <= 0) {
                $validator->errors()->add('amount', 'Opening amount must be greater than 0.');
            }
        });
    }
}
