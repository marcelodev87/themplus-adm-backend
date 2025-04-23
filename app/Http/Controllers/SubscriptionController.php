<?php

namespace App\Http\Controllers;

use App\Http\Resources\External\Subscription\SubscriptionTableResource;
use App\Repositories\External\SubscriptionExternalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubscriptionController
{
    private $repository;

    private $rule;

    public function __construct(SubscriptionExternalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {
            $subscriptions = $this->repository->getAll();

            return response()->json(['subscriptions' => SubscriptionTableResource::collection($subscriptions)], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas as assinaturas: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|string',
                'price' => 'required|numeric',
            ];

            $messages = [
                'id.required' => 'O ID do cupom é obrigatório',
                'id.string' => 'O ID do cupom deve ser uma string',
                'price.required' => 'O preço é obrigatório',
                'price.numeric' => 'O preço deve ser um número válido',
            ];

            $validator = Validator::make($request->only(['id', 'price']), $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator, response()->json(['errors' => $validator->errors()], 422));
            }

            DB::beginTransaction();

            $data = ['price' => $request->input('price')];
            $subscription = $this->repository->update($request->input('id'), $data);

            if ($subscription) {
                DB::commit();

                $subscriptions = $this->repository->getAll();

                return response()->json([
                    'subscriptions' => SubscriptionTableResource::collection($subscriptions),
                    'message' => 'Assinatura atualizada com sucesso',
                ], 200);
            }

            throw new \Exception('Falha ao atualizar assinatura');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar assinatura: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
