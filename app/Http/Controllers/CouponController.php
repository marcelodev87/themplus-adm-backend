<?php

namespace App\Http\Controllers;

use App\Http\Resources\Internal\Coupon\CouponShowResource;
use App\Http\Resources\Internal\Coupon\CouponTableResource;
use App\Models\Internal\Coupon;
use App\Repositories\Internal\CouponRepository;
use App\Rules\CouponRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponController
{
    private $repository;

    private $rule;

    public function __construct(CouponRepository $repository, CouponRule $rule)
    {
        $this->repository = $repository;
        $this->rule = $rule;
    }

    public function index()
    {
        try {
            $coupons = $this->repository->getAll();

            return response()->json([
                'coupons' => CouponTableResource::collection($coupons),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas os cupons: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(Coupon $id)
    {
        try {
            return response()->json([
                'coupon' => new CouponShowResource($id),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar cupom '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->rule->store($request);

            $data = [
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'service' => $request->input('service'),
                'subscription_id' => null,
                'discount' => $request->input('discount'),
                'date_expiration' => $request->input('dateExpiration'),
            ];

            if ($request->input('type') === 'subscription') {
                $subscription = DB::connection('external')
                    ->table('subscriptions')
                    ->where('name', $request->input('subscription'))
                    ->first();

                if (! $subscription) {
                    throw new \Exception('Assinatura não encontrada.');
                }

                $data['subscription_id'] = $subscription->id;
            }
            $coupon = $this->repository->create($data);

            if ($coupon) {
                DB::commit();
                $coupons = $this->repository->getAll();

                return response()->json(['coupons' => CouponTableResource::collection($coupons), 'message' => 'Cupom criado com sucesso'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar cupom: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->rule->update($request);

            $data = [
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'service' => $request->input('service'),
                'subscription_id' => null,
                'discount' => $request->input('discount'),
                'date_expiration' => $request->input('dateExpiration'),
            ];

            if ($request->input('type') === 'subscription') {
                $subscription = DB::connection('external')
                    ->table('subscriptions')
                    ->where('name', $request->input('subscription'))
                    ->first();

                $data['subscription_id'] = $subscription->id;
            }

            $coupon = $this->repository->update($request->input('id'), $data);

            if ($coupon) {
                DB::commit();

                $coupons = $this->repository->getAll();

                return response()->json(['coupons' => CouponTableResource::collection($coupons), 'message' => 'Cupom atualizado com sucesso'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cupom: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $this->rule->delete($id);
            $enterprise = $this->repository->delete($id);

            if ($enterprise) {
                DB::commit();

                return response()->json(['message' => 'Cupom deletada com sucesso'], 200);
            }

            throw new \Exception('Falha ao deletar cupom');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar cupom: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
