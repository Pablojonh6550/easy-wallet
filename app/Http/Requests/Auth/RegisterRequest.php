<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always returns true as all users are authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array The rules for validating the request.
     *
     * @see https://laravel.com/docs/master/validation#available-validation-rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password|min:8|string',
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array An array of custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'email.exists' => 'O email informado não existe.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'O campo senha deve ter no mínimo 8 caracteres.',
            'password_confirmation.required' => 'O campo confirmação de senha é obrigatório.',
            'password_confirmation.same' => 'O campo confirmação de senha deve ser igual ao campo senha.',
            'password_confirmation.min' => 'O campo confirmação de senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
