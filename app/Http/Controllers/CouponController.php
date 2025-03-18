<?php

namespace App\Http\Controllers;

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
                'coupons' => $coupons,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas os cupons: ' . $e->getMessage());

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
                'movements' => $request->input('movements'),
                'allow_financial' => $request->input('allow_financial'),
                'allow_members' => $request->input('allow_members'),
                'allowAssistantWhatsapp' => $request->input('allow_assistant_whatsapp'),
                'discount' => $request->input('discount'),
                'date_expires' => $request->input('dateExpires'),
            ];
            $coupon = $this->repository->create($data);

            if ($coupon) {
                DB::commit();
                return response()->json(['coupon' => $coupon, 'message' => 'Cupom criado com sucesso'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar cupom: ' . $e->getMessage());

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
                'movements' => $request->input('movements'),
                'allow_financial' => $request->input('allow_financial'),
                'allow_members' => $request->input('allow_members'),
                'allow_assistant_whatsapp' => $request->input('allow_assistant_whatsapp'),
                'discount' => $request->input('discount'),
                'date_expires' => $request->input('dateExpires'),
            ];
            $coupon = $this->repository->update($request->input('id'), $data);

            if ($coupon) {
                DB::commit();
                return response()->json(['coupon' => $coupon, 'message' => 'Cupom atualizado com sucesso'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cupom: ' . $e->getMessage());

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

            Log::error('Erro ao deletar cupom: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
