<?php

namespace App\Http\Requests\AccountBalanceHistory;

use App\Enums\AccountHistoryReferenceType;
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
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'reference_type' => ['required', 'string', Rule::in(AccountHistoryReferenceType::manualValues())],
            'amount' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->reference_type === AccountHistoryReferenceType::MANUAL_ADD->value) {
            $this->merge([
                'amount' => $this->amount !== null && $this->amount !== '' ? (int) $this->amount : null,
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->reference_type === AccountHistoryReferenceType::MANUAL_ADD->value && (int) $this->amount <= 0) {
                $validator->errors()->add('amount', 'Add amount must be greater than 0.');
            }
        });
    }
}
