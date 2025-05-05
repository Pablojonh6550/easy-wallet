<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'account' => 'required|numeric',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O campo de valor da transferência é obrigatório.',
            'account.required' => 'O campo conta de destino é obrigatório.',
            'password.required' => 'O campo password é obrigatório.',
            'password.min' => 'O campo password deve ter no mínimo 8 caracteres.',
        ];
    }
}
