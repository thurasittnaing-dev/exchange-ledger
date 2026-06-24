<?php

namespace App\Http\Requests\User;

use App\Enums\Positions;
use App\Enums\Roles;
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
        $fileMaxSize = config('app.photo_max_size') * 1024;
        $userId = $this->route('user') instanceof \App\Models\Management\User
            ? $this->route('user')->id
            : $this->route('user');

        return [
            'name' => ['required', 'string', 'max:100'],
            'username'   => [
                'required',
                'string',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'email' => ['nullable', 'email'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:' . $fileMaxSize],
        ];
    }
}
