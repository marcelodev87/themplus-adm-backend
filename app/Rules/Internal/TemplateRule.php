<?php

namespace App\Rules\Internal;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TemplateRule
{
    public function create($request)
    {
        $rules = [
            'title' => 'required|string',
            'text' => 'required|string|max:10000',
        ];

        $messages = [
            'title.required' => 'O título do template é obrigatório',
            'title.string' => 'O título deve ser uma string',
            'text.required' => 'O texto do template é obrigatório',
            'text.string' => 'O texto deve ser uma string',
            'text.max' => 'O nome do template não pode ter mais de 10000 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function update($request)
    {
        $rules = [
            'id' => 'required|string|max:100|exists:notification_templates,id',
            'title' => 'required|string',
            'text' => 'required|string|max:10000',
        ];

        $messages = [
            'id.required' => 'O ID do template é obrigatório',
            'id.string' => 'O ID do template deve ser uma string',
            'id.max' => 'O ID do template não pode ter mais de 100 caracteres',
            'id.exists' => 'O ID do template não existe.',
            'title.required' => 'O título do template é obrigatório',
            'title.string' => 'O título deve ser uma string',
            'text.required' => 'O texto do template é obrigatório',
            'text.string' => 'O texto deve ser uma string',
            'text.max' => 'O nome do template não pode ter mais de 10000 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function delete($id)
    {
        $rules = [
            'id' => 'required|string|max:100|exists:notification_templates,id',
        ];

        $messages = [
            'id.required' => 'O ID da organização é obrigatória',
            'id.string' => 'O ID da organização deve ser uma string',
            'id.exists' => 'O ID do template não existe.',
        ];

        $validator = Validator::make(['id' => $id], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
        }

        return true;
    }
}
