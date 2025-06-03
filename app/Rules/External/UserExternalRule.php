<?php

namespace App\Rules\External;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserExternalRule
{
            public function updateMemberUser($request)
    {
        $rules = [
            'id' => 'required|string|max:100',
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:50',
            'position' => 'required|string',
            'active' => 'required|numeric'
        ];

        $messages = [
            'id.required' => 'O ID do membro é obrigatório',
            'id.string' => 'O ID do membro deve ser uma string',
            'id.max' => 'O ID do membro não pode ter mais de 100 caracteres',
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser uma string',
            'name.min' => 'O nome não pode ter menos de 3 caracteres',
            'name.max' => 'O nome não pode ter mais de 30 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.string' => 'O e-mail deve ser uma string',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido',
            'email.max' => 'O e-mail não pode ter mais de 50 caracteres',
            'position.required' => 'O cargo do novo usuário é obrigatório',
            'position.string' => 'O cargo do novo usuário deve ser uma string',
            'active.requerid' => 'O status do novo usuário é obrigatório',
            'active.requerid' => 'O status do novo usuário deve ser um número',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
