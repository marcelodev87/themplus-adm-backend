<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CouponRule
{
    public function update($request)
    {
        $rules = [
            'id' => 'required|string|exists:coupons,id',
            'name' => 'required|string|min:3|max:30',

        ];

        $messages = [
            'id.required' => 'O ID do cupom é obrigatório.',
            'id.string' => 'O ID do cupom deve ser uma string.',
            'id.exists' => 'O ID do cupom não existe.',
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    public function store($request)
    {
        $rules = [
            'name' => 'required|string|min:3|max:30',
            'code' => 'required|string|min:6|max:30',
            'type' => 'required|string|in:subscription,service',
            'service' => 'nullable|required_if:type,service|string',
            'subscription' => 'nullable|required_if:type,subscription|string',
            'discount' => 'nullable|integer|min:1|max:100',
            'dateExpiration' => 'nullable|date_format:d/m/Y',
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 6 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',
            'code.required' => 'O código do cupom é obrigatório.',
            'code.string' => 'O código do cupom deve ser uma string.',
            'code.min' => 'O código do cupom deve ter no mínimo 3 caracteres.',
            'code.max' => 'O código do cupom deve ter no máximo 30 caracteres.',
            'type.required' => 'O tipo de cupom é obrigatório.',
            'type.string' => 'O tipo de cupom deve ser uma string.',
            'type.in' => 'O tipo de cupom deve ser "subscription" ou "service".',
            'service.required_if' => 'O campo "service" é obrigatório quando o tipo de cupom for "service".',
            'service.string' => 'O campo "service" deve ser uma string.',
            'subscription.required_if' => 'O campo "subscription" é obrigatório quando o tipo de cupom for "subscription".',
            'subscription.string' => 'O campo "subscription" deve ser uma string.',
            'discount.integer' => 'O desconto deve ser um número inteiro.',
            'discount.min' => 'O desconto deve ser no mínimo 1.',
            'discount.max' => 'O desconto deve ser no máximo 100.',
            'dateExpiration.date_format' => 'A data de expiração deve estar no formato dd/mm/yyyy.',
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
            'id' => 'required|string',
        ];

        $messages = [
            'id.required' => 'O ID do cupom é obrigatória',
            'id.string' => 'O ID do cupom deve ser uma string',
        ];

        $validator = Validator::make(['id' => $id], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
        }

        return true;
    }
}
