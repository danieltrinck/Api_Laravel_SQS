<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestSendMessageSQS extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Mercadolivre'          => 'required',
            'Mercadolivre.auth'     => 'required',
            'Mercadolivre.type'     => 'required',
            'Mercadolivre.domain'   => 'required',
            'Mercadolivre.store_id' => 'required',
            'Mercadolivre.products' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Mercadolivre.required' => 'Os campos enviados estão no formato inválido.',
            'Mercadolivre.auth'     => 'O campo Mercadolivre.auth.token_irroba é obrigatório.',
            'Mercadolivre.type'     => 'O campo Mercadolivre.type é obrigatório.',
            'Mercadolivre.domain'   => 'O campo Mercadolivre.domain é obrigatório.',
            'Mercadolivre.products' => 'O campo Mercadolivre.products é obrigatório.'
        ];
    }
}
