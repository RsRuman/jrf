<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|min:3|max:55',
            'email'                 => 'required|email|max:55|unique:users',
            'password'              => 'required|string|min:8|max:55|confirmed',
            'password_confirmation' => 'required|string|min:8|max:55',
        ];
    }
}
