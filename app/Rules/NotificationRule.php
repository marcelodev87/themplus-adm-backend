<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NotificationRule
{
    public function sendNotification($request)
    {
        $rules = [
            'title' => 'required|string|max:100',
            'text' => 'required|string|max:10000',
            'enterprisesId' => 'required|array',
        ];

        $messages = [
            'title.required' => 'O título é obrigatório',
            'title.string' => 'O título deve ser uma string',
            'title.max' => 'O título não pode ter mais de 100 caracteres',
            'text.required' => 'O texto é obrigatório',
            'text.string' => 'O texto deve ser uma string',
            'text.max' => 'O texto não pode ter mais de 10000 caracteres',
            'enterprisesId.required' => 'Os ID das organizações são obrigatórias',
            'enterprisesId.array' => 'Os ID das organizações devem ser um array',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
