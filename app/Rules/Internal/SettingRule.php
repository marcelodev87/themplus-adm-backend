<?php

namespace App\Rules\Internal;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SettingRule
{
    public function update($request)
    {
        $rules = [
            'allow_feedback_saved' => 'required|string|in:0,1',
        ];

        $messages = [
            'allow_feedback_saved.required' => 'O valor(value) é obrigatório',
            'allow_feedback_saved.string' => 'O valor(value) deve ser uma string',
            'allow_feedback_saved.in' => 'O valor(value) só recebe 0 ou 1',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
