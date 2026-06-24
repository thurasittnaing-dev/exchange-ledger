<?php

namespace App\Http\Requests\Transaction;

use App\Enums\FeeType;
use App\Enums\TransactionType;
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
            'type' => ['required', 'string', Rule::in(array_column(TransactionType::cases(), 'value'))],
            'fee_type' => ['required', 'string', Rule::in(array_column(FeeType::cases(), 'value'))],
            'amount' => ['required', 'integer', 'min:1'],
            'fee_amount' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->fee_type === FeeType::NET->value && (int) $this->fee_amount >= (int) $this->amount) {
                $validator->errors()->add('fee_amount', 'Fee amount must be less than transaction amount for net fee type.');
            }
        });
    }
}
