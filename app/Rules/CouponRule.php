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
            'movements' => 'required|integer|min:30|max:999',
            'allowFinancial' => 'required|integer|in:0,1',
            'allowMembers' => 'required|integer|in:0,1',
            'allowAssistantWhatsapp' => 'required|integer|in:0,1',
            'discount' => 'nullable|integer|min:1|max:100',
            'dateExpires' => 'nullable|date_format:d/m/Y',
        ];

        $messages = [
            'id.required' => 'O ID do cupom é obrigatório.',
            'id.string' => 'O ID do cupom deve ser uma string.',
            'id.exists' => 'O ID do cupom não existe',

            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',

            'movements.required' => 'O número de movimentos é obrigatório.',
            'movements.integer' => 'O número de movimentos deve ser um número inteiro.',
            'movements.min' => 'O número de movimentos deve ser no mínimo 31.',
            'movements.max' => 'O número de movimentos deve ser no máximo 999.',

            'allowFinancial.required' => 'A permissão financeira é obrigatória.',
            'allowFinancial.integer' => 'A permissão financeira deve ser um número inteiro.',
            'allowFinancial.in' => 'A permissão financeira deve ser 0 ou 1.',

            'allowMembers.required' => 'A permissão de membros é obrigatória.',
            'allowMembers.integer' => 'A permissão de membros deve ser um número inteiro.',
            'allowMembers.in' => 'A permissão de membros deve ser 0 ou 1.',

            'allowAssistantWhatsapp.required' => 'A permissão de assistente WhatsApp é obrigatória.',
            'allowAssistantWhatsapp.integer' => 'A permissão de assistente WhatsApp deve ser um número inteiro.',
            'allowAssistantWhatsapp.in' => 'A permissão de assistente WhatsApp deve ser 0 ou 1.',

            'discount.integer' => 'O desconto deve ser um número inteiro.',
            'discount.min' => 'O desconto deve ser no mínimo 1.',
            'discount.max' => 'O desconto deve ser no máximo 100.',

            'dateExpires.date_format' => 'A data de expiração deve estar no formato dd/mm/yyyy.',
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
            'movements' => 'required|integer|min:30|max:999',
            'allowFinancial' => 'required|integer|in:0,1',
            'allowMembers' => 'required|integer|in:0,1',
            'allowAssistantWhatsapp' => 'required|integer|in:0,1',
            'discount' => 'nullable|integer|min:1|max:100',
            'dateExpires' => 'nullable|date_format:d/m/Y',
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',

            'movements.required' => 'O número de movimentos é obrigatório.',
            'movements.integer' => 'O número de movimentos deve ser um número inteiro.',
            'movements.min' => 'O número de movimentos deve ser no mínimo 31.',
            'movements.max' => 'O número de movimentos deve ser no máximo 999.',

            'allowFinancial.required' => 'A permissão financeira é obrigatória.',
            'allowFinancial.integer' => 'A permissão financeira deve ser um número inteiro.',
            'allowFinancial.in' => 'A permissão financeira deve ser 0 ou 1.',

            'allowMembers.required' => 'A permissão de membros é obrigatória.',
            'allowMembers.integer' => 'A permissão de membros deve ser um número inteiro.',
            'allowMembers.in' => 'A permissão de membros deve ser 0 ou 1.',

            'allowAssistantWhatsapp.required' => 'A permissão de assistente WhatsApp é obrigatória.',
            'allowAssistantWhatsapp.integer' => 'A permissão de assistente WhatsApp deve ser um número inteiro.',
            'allowAssistantWhatsapp.in' => 'A permissão de assistente WhatsApp deve ser 0 ou 1.',

            'discount.integer' => 'O desconto deve ser um número inteiro.',
            'discount.min' => 'O desconto deve ser no mínimo 1.',
            'discount.max' => 'O desconto deve ser no máximo 100.',

            'dateExpires.date_format' => 'A data de expiração deve estar no formato dd/mm/yyyy.',
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
