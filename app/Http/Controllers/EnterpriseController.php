<?php

namespace App\Http\Controllers;

use App\Helpers\EnterpriseHelper;
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

    public function index(Request $request)
    {
        try {
            $enterprises = $this->repository->getAll();

            return response()->json([
                'enterprises' => $enterprises,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas as filiais: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function showViewEnterprises(Request $request)
    {
        try {
            $enterpriseId = $request->user()->enterprise_id;
            $viewEnterpriseId = $request->user()->view_enterprise_id;

            $enterprises = $this->repository->getAllViewEnterprises($enterpriseId);
            $enterprises = $enterprises->map(function ($enterprise) use ($viewEnterpriseId) {
                $enterprise->selected = $enterprise->id === $viewEnterpriseId;

                return $enterprise;
            });

            return response()->json(['enterprises' => $enterprises], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas as opções de visualização de organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeOffice(Request $request)
    {
        try {
            DB::beginTransaction();
            $office = $this->service->createOffice($request);

            $register = RegisterHelper::create(
                $request->user()->id,
                $request->user()->enterprise_id,
                'created',
                'office',
                "{$office->name}"
            );

            if ($office && $register) {
                DB::commit();

                $enterpriseId = $request->user()->enterprise_id;
                $offices = $this->repository->getAllOfficesByEnterprise($enterpriseId);

                return response()->json(['offices' => $offices, 'message' => 'Filial cadastrada com sucesso'], 201);
            }

            throw new \Exception('Falha ao criar filial');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar filial: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeByCounter(Request $request)
    {
        try {
            DB::beginTransaction();
            $enteprise = $this->service->createByCounter($request);

            if ($enteprise) {
                DB::commit();

                return response()->json(['message' => 'Organização cadastrada com sucesso'], 201);
            }

            throw new \Exception('Falha ao criar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function saveViewEnterprise(Request $request)
    {
        try {
            DB::beginTransaction();
            $view = $this->service->updateViewEnterprise($request);

            if ($view) {
                DB::commit();

                $enterpriseId = $request->user()->enterprise_id;
                $viewEnterpriseId = $request->user()->view_enterprise_id;

                $enterprises = $this->repository->getAllViewEnterprises($enterpriseId);
                $enterprises = $enterprises->map(function ($enterprise) use ($viewEnterpriseId) {
                    $enterprise->selected = $enterprise->id === $viewEnterpriseId;

                    return $enterprise;
                });

                $user = $this->userRepository->findById($request->user()->id);

                $enterpriseView = $this->repository->findById($user->view_enterprise_id);
                $user->view_enterprise_name = $enterpriseView->name;

                return response()->json([
                    'enterprises' => $enterprises,
                    'user' => $user,
                    'message' => 'Visualização de organização alterada com sucesso',
                ], 200);
            }

            throw new \Exception('Falha ao criar filial');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar filial: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $enterpriseId = $request->user()->enterprise_id;
            $enterprise = $this->repository->findById($enterpriseId);

            return response()->json(['enterprise' => $enterprise], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function filter($id)
    {
        try {
            $enterprise = $this->repository->findById($id);

            return response()->json(['counter' => $enterprise], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da organização de contabilidade: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request, $text)
    {
        try {
            $enterpriseId = $request->user()->enterprise_id;
            $enterprises = $this->repository->searchEnterprise($enterpriseId, $text);

            return response()->json(['enterprises' => $enterprises], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $enterprise = $this->service->update($request);

            $register = RegisterHelper::create(
                $request->user()->id,
                $request->user()->enterprise_id,
                'updated',
                'enterprise',
                "{$request->user()->enterprise->name}"
            );

            if ($enterprise && $register) {
                DB::commit();

                return response()->json(['enterprise' => $enterprise, 'message' => 'Organização atualizada com sucesso'], 200);
            }

            throw new \Exception('Falha ao atualizar organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateCodeFinancial(Request $request)
    {
        try {
            DB::beginTransaction();
            $enterprise = $this->service->updateCodeFinancial($request);

            $register = RegisterHelper::create(
                $request->user()->id,
                $request->user()->enterprise_id,
                'updated',
                'enterprise',
                "{$request->user()->enterprise->name}"
            );

            if ($enterprise && $register) {
                DB::commit();

                $bonds = $this->enterpriseRepository->getBonds($request->user()->enterprise_id);
                $bonds = $bonds->map(function ($bond) {
                    $bond->no_verified = $this->financialRepository->countNoVerified($bond->id);
                    $bond->manage_users = $this->settingsCounterRepository->verifyAllowManage($bond->id);

                    return $bond;
                });

                return response()->json(['bonds' => $bonds, 'message' => 'Código interno da organização atualizada com sucesso'], 200);
            }

            throw new \Exception('Falha ao atualizar código interno da organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar código interno da organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function unlink(Request $request)
    {
        try {
            DB::beginTransaction();

            $enterprise = $this->service->unlink($request);
            $register = RegisterHelper::create(
                $request->user()->id,
                $request->user()->enterprise_id,
                'unlink',
                'order',
                "{$request->user()->enterprise->name}"
            );

            if ($enterprise && $register) {
                DB::commit();

                return response()->json(['message' => 'Organização de contabilidade removida com sucesso'], 200);
            }

            throw new \Exception('Falha ao remover organização de contabilidade');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao remover organização de contabilidade: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyOffice(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $this->rule->deleteOffice($id);
            $enterpriseForDelete = $this->repository->findById($id);
            $enterprise = $this->repository->deleteOffice($id);

            $register = RegisterHelper::create(
                $request->user()->id,
                $request->user()->enterprise_id,
                'deleted',
                'office',
                "{$enterpriseForDelete->name}"
            );

            if ($enterprise && $register) {
                DB::commit();

                return response()->json(['message' => 'Filial deletada com sucesso'], 200);
            }

            throw new \Exception('Falha ao deletar filial');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar filial: ' . $e->getMessage());

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

            Log::error('Erro ao deletar organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
