<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EnterpriseRule
{
    public function update($request)
    {
        $rules = [
            'id' => 'required|string|max:100',
            'name' => 'required|string|min:3|max:80',
        ];

        $messages = [
            'id.required' => 'O ID da organização é obrigatório',
            'id.string' => 'O ID da organização deve ser uma string',
            'id.max' => 'O ID da organização não pode ter mais de 100 caracteres',
            'name.required' => 'O nome da organização é obrigatório',
            'name.string' => 'O nome deve ser uma string',
            'name.min' => 'O nome da organização não pode ter menos de 3 caracteres',
            'name.max' => 'O nome da organização não pode ter mais de 30 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function updateCoupon($request)
    {
        $rules = [
            'id' => 'required|string|max:100',
            'coupon_id' => 'required|string|min:3|max:80',
        ];

        $messages = [
            'id.required' => 'O ID da organização é obrigatório',
            'id.string' => 'O ID da organização deve ser uma string',
            'id.max' => 'O ID da organização não pode ter mais de 100 caracteres',
            'coupon_id.required' => 'O ID do cupom é obrigatório',
            'coupon_id.string' => 'O ID do cupom deve ser uma string',
            'coupon_id.max' => 'O ID do cupom não pode ter mais de 100 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function show($id)
    {
        $rules = [
            'id' => 'required|string',
        ];

        $messages = [
            'id.required' => 'O ID da organização é obrigatória',
            'id.string' => 'O ID da organização deve ser uma string',
        ];

        $validator = Validator::make(['id' => $id], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
        }

        return true;
    }

    public function delete($id)
    {
        $rules = [
            'id' => 'required|string',
        ];

        $messages = [
            'id.required' => 'O ID da organização é obrigatória',
            'id.string' => 'O ID da organização deve ser uma string',
        ];

        $validator = Validator::make(['id' => $id], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
        }

        return true;
    }
}
