<?php

namespace App\Http\Requests\District;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'division_id' => ['required'],
            'name_en' => ['required', 'string', 'max:255',],
            'name_mm' => ['required', 'string', 'max:255',],
            'code'   => [
                'nullable',
                'string',
                'max:32',
                Rule::unique('districts', 'code')->ignore($this->route('district'))
            ],
        ];
    }
}
