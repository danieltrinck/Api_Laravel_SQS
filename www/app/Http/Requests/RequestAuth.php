<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestAuth extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:5',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'O e-mail deve ser um endereço de e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.min'      => 'A senha deve ter pelo menos 8 caracteres.',
        ];
    }
}
