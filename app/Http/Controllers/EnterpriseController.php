<?php

namespace App\Http\Controllers;

use App\Http\Resources\External\EnterpriseSelectResource;
use App\Http\Resources\Internal\Coupon\CouponEnterpriseResource;
use App\Repositories\External\EnterpriseExternalRepository;
use App\Repositories\External\EnterpriseHasCouponExternalRepository;
use App\Repositories\External\UserExternalRepository;
use App\Rules\EnterpriseRule;
use App\Services\External\EnterpriseExternalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnterpriseController
{
    private $service;

    private $repository;

    private $userExternalRepository;

    private $enterpriseHasCouponExternalRepository;

    private $rule;

    public function __construct(EnterpriseExternalService $service, EnterpriseExternalRepository $repository, EnterpriseRule $rule, EnterpriseHasCouponExternalRepository $enterpriseHasCouponExternalRepository, UserExternalRepository $userExternalRepository)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->userExternalRepository = $userExternalRepository;
        $this->rule = $rule;
        $this->enterpriseHasCouponExternalRepository = $enterpriseHasCouponExternalRepository;
    }

    public function index()
    {
        try {
            $enterprises = $this->repository->getAll();

            return response()->json([
                'enterprises' => $enterprises,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas as filiais: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function indexSelected()
    {
        try {
            $enterprises = $this->repository->getAll();

            return response()->json([
                'enterprises' => EnterpriseSelectResource::collection($enterprises),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas as organizações: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $this->rule->show($id);
            $enterprise = $this->repository->findByIdWithRelations($id);

            return response()->json(['enterprise' => $enterprise], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getMembersByEnterprise($id)
    {
        try {
            $members = $this->userExternalRepository->getMembersByEnterprise($id);

            return response()->json([
                'members' => $members,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar membros da organização: '.$e->getMessage());
        }
    }

    public function getCouponsInEnterprise($id)
    {
        try {
            $this->rule->show($id);
            $coupons = $this->enterpriseHasCouponExternalRepository->getCouponsByEnterprise($id);

            return response()->json(['coupons' => CouponEnterpriseResource::collection($coupons)], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar cupons da organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $enteprise = $this->service->create($request);

            if ($enteprise) {
                DB::commit();

                $enterprises = $this->repository->getAll();

                return response()->json(['enterprises' => $enterprises, 'message' => 'Organização cadastrada com sucesso'], 201);
            }

            throw new \Exception('Falha ao criar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $enterprise = $this->service->update($request);

            if ($enterprise) {
                DB::commit();

                $enterprises = $this->repository->getAll();

                return response()->json(['enterprises' => $enterprises, 'message' => 'Organização atualizada com sucesso'], 200);
            }

            throw new \Exception('Falha ao atualizar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function setCoupon(Request $request)
    {
        try {
            DB::beginTransaction();
            $coupon = $this->service->setCoupon($request);

            if ($coupon) {
                DB::commit();

                $coupons = $this->enterpriseHasCouponExternalRepository->getCouponsByEnterprise($request->input('enterprise_id'));

                return response()->json(['coupons' => CouponEnterpriseResource::collection($coupons), 'message' => 'Vínculo com cupom atualizado com sucesso'], 200);
            }

            throw new \Exception('Falha ao vincular cupom a organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao vincular cupom a organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyCouponByEnterprise($id)
    {
        try {
            DB::beginTransaction();

            $this->rule->destroyCouponByEnterprise($id);
            $coupon = $this->repository->destroyCouponByEnterprise($id);

            if ($coupon) {
                DB::commit();

                return response()->json(['message' => 'Cupom deletado da organização com sucesso'], 200);
            }

            throw new \Exception('Falha ao deletar cupom da organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar cupom da organização: '.$e->getMessage());

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

                return response()->json(['message' => 'Organização deletada com sucesso'], 200);
            }

            throw new \Exception('Falha ao deletar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
