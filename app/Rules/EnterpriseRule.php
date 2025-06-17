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

    public function setCoupon($request)
    {
        $rules = [
            'enterpriseId' => 'required|string|max:100',
            'couponId' => 'nullable|string|min:3|max:80|exists:coupons,id',
        ];

        $messages = [
            'enterpriseId.required' => 'O ID da organização é obrigatório',
            'enterpriseId.string' => 'O ID da organização deve ser uma string',
            'enterpriseId.max' => 'O ID da organização não pode ter mais de 100 caracteres',
            'couponId.required' => 'O ID do cupom é obrigatório',
            'couponId.string' => 'O ID do cupom deve ser uma string',
            'couponId.max' => 'O ID do cupom não pode ter mais de 100 caracteres',
            'couponId.exists' => 'O ID do cupom não existe',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

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

    public function destroyCouponByEnterprise($id)
    {
        $rules = [
            'id' => 'required|string',
        ];

        $messages = [
            'id.required' => 'O ID do vínculo de cupom é obrigatória',
            'id.string' => 'O ID do vínculo de cupom deve ser uma string',
        ];

        $validator = Validator::make(['id' => $id], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
        }

        return true;
    }
}
