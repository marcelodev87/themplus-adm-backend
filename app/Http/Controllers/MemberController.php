<?php

namespace App\Http\Controllers;

use App\Http\Resources\Internal\MemberResource;
use App\Repositories\Internal\UserRepository;
use App\Rules\UserRule;
use App\Services\Internal\UserService;
use Illuminate\Support\Facades\Log;

class MemberController
{
    private $service;

    private $repository;

    private $rule;

    public function __construct(UserService $service, UserRepository $repository, UserRule $rule)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->rule = $rule;
    }

    public function index()
    {
        try {
            $users = $this->repository->getAll();

            return response()->json([
                'users' => MemberResource::collection($users),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas os membros de sua organização: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->repository->findById($id);

            return response()->json(['user' => MemberResource::collection($user)], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados do usuário: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $user = $this->service->include($request);
    //         $register = RegisterHelper::create(
    //             $request->user()->id,
    //             $request->user()->enterprise_id,
    //             'created',
    //             'member',
    //             "{$user->name}|{$user->email}"
    //         );

    //         if ($user && $register) {
    //             DB::commit();
    //             $dataNotification = [
    //                 'user_id' => $user->id,
    //                 'enterprise_id' => $user->enterprise_id,
    //                 'title' => 'Boas vindas ao Themplus',
    //                 'text' => 'Seja bem-vindo ao Themplus! Você acaba de dar o primeiro passo para gerenciar melhor suas movimentações e simplificar a burocracia da sua contabilidade de modo mais fácil. Estamos aqui para ajudar você a ter uma experiência mais organizada e eficiente. Aproveite todos os recursos que preparamos para otimizar a sua gestão!',
    //             ];
    //             $this->notificationRepository->createForUser($dataNotification);

    //             $enterpriseId = $request->user()->enterprise_id !== $request->user()->view_enterprise_id ? $request->user()->view_enterprise_id : $request->user()->enterprise_id;
    //             $users = $this->repository->getAllByEnterpriseWithRelations($enterpriseId);

    //             return response()->json(['users' => UserResource::collection($users), 'message' => 'Membro adicionado á sua organização com sucesso'], 201);
    //         }

    //         throw new \Exception('Falha ao criar membro da organização');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Erro ao registrar membro da organização: ' . $e->getMessage());

    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }

    // public function update(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $member = $this->repository->findById($request->input('id'));
    //         $user = $this->service->updateMember($request);
    //         $register = RegisterHelper::create(
    //             $request->user()->id,
    //             $request->user()->enterprise_id,
    //             'updated',
    //             'member',
    //             "{$member->name}|{$member->email}"
    //         );

    //         if ($user && $register) {
    //             DB::commit();

    //             $enterpriseId = $request->user()->enterprise_id !== $request->user()->view_enterprise_id ? $request->user()->view_enterprise_id : $request->user()->enterprise_id;
    //             $users = $this->repository->getAllByEnterpriseWithRelations($enterpriseId);

    //             return response()->json(['users' => UserResource::collection($users), 'message' => 'Dados do membro foram atualizados com sucesso'], 200);
    //         }

    //         throw new \Exception('Falha ao atualizar dados do membro');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Erro ao atualizar dados do membro: ' . $e->getMessage());

    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }

    // public function active(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $member = $this->repository->update($request->input('userId'), ['active' => $request->input('active')]);

    //         $register = RegisterHelper::create(
    //             $request->user()->id,
    //             $request->user()->enterprise_id,
    //             $request->input('active') === 1 ? 'reactivated' : 'inactivated',
    //             'member',
    //             "{$member->name}|{$member->email}"
    //         );
    //         if ($member && $register) {
    //             DB::commit();

    //             $enterpriseId = $request->user()->enterprise_id !== $request->user()->view_enterprise_id ? $request->user()->view_enterprise_id : $request->user()->enterprise_id;
    //             $users = $this->repository->getAllByEnterpriseWithRelations($enterpriseId);

    //             $message = $request->input('active') == 0 ? 'Usuário inativado com sucesso' : 'Usuário ativado com sucesso';

    //             return response()->json(['users' => UserResource::collection($users), 'message' => $message], 200);
    //         }

    //         throw new \Exception('Falha ao atualizar dados do membro');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Erro ao atualizar dados do membro: ' . $e->getMessage());

    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }

    // public function destroy(Request $request, string $id)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $this->rule->delete($id);
    //         $memberDelete = $this->repository->findById($id);
    //         $member = $this->repository->delete($id);

    //         $register = RegisterHelper::create(
    //             $request->user()->id,
    //             $request->user()->enterprise_id,
    //             'deleted',
    //             'member',
    //             "{$memberDelete->name}|{$memberDelete->email}"
    //         );

    //         if ($member && $register) {
    //             DB::commit();

    //             return response()->json(['message' => 'Membro deletado com sucesso'], 200);
    //         }

    //         throw new \Exception('Falha ao deletar membro');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Erro ao deletar membro: ' . $e->getMessage());

    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
}
