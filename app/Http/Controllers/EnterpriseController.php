<?php

namespace App\Http\Controllers;

use App\Repositories\External\EnterpriseExternalRepository;
use App\Rules\EnterpriseRule;
use App\Services\External\EnterpriseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnterpriseController
{
    private $service;

    private $repository;

    private $rule;

    public function __construct(EnterpriseService $service, EnterpriseExternalRepository $repository, EnterpriseRule $rule)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->rule = $rule;
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

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $enterprise = $this->service->update($request);

            if ($enterprise) {
                DB::commit();

                return response()->json(['enterprise' => $enterprise, 'message' => 'Organização atualizada com sucesso'], 200);
            }

            throw new \Exception('Falha ao atualizar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateCoupon(Request $request)
    {
        try {
            DB::beginTransaction();
            $enterprise = $this->service->updateCoupon($request);

            if ($enterprise) {
                DB::commit();

                $enterprises = $this->repository->getAll();

                return response()->json(['enterprises' => $enterprises, 'message' => 'Vínculo com cupom atualizado com sucesso'], 200);
            }

            throw new \Exception('Falha ao vincular cupom a organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao vincular cupom a organização: '.$e->getMessage());

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
