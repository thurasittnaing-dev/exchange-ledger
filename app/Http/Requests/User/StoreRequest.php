<?php

namespace App\Http\Requests\User;

use App\Enums\Positions;
use App\Enums\Roles;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        $fileMaxSize = config('app.photo_max_size') * 1024;
        return [
        'name' => ['required', 'string', 'max:100'],
            'username'   => [
                'required',
                'string',
                Rule::unique('users', 'username')
            ],
            'email' => ['nullable', 'email'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:' . $fileMaxSize],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }
}
