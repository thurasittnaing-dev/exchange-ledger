<?php

namespace App\Http\Requests\Division;

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
        $divisionId = $this->route('division');

        return [
            'name_en' => [
                'required', 'string', 'max:32',
                Rule::unique('divisions', 'name_en')->ignore($divisionId)
            ],
            'name_mm' => [
                'required', 'string', 'max:32',
                Rule::unique('divisions', 'name_mm')->ignore($divisionId)
            ],
            'code' => [
                'required', 'string', 'max:32',
                Rule::unique('divisions', 'code')->ignore($divisionId)
            ],
            'level' => [
                'nullable', 'string', 'max:16',
            ],
        ];
    }
}
