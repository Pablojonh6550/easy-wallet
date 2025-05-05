<?php

namespace App\Http\Requests\Reverse;

use Illuminate\Foundation\Http\FormRequest;

class ReverseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|numeric',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'O id da transação é obrigatório.',
            'password.required' => 'O campo password é obrigatório.',
            'password.min' => 'O campo password deve ter no mínimo 8 caracteres.',
        ];
    }
}
