<?php

namespace App\Http\Controllers;

use App\Http\Resources\Internal\MemberResource;
use App\Repositories\Internal\UserRepository;
use App\Rules\UserRule;
use App\Services\Internal\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            Log::error('Erro ao buscar todas os membros de sua organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->repository->findById($id);

            return response()->json(['user' => MemberResource::collection($user)], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados do usuário: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->service->include($request);

            if ($user) {
                DB::commit();
                $users = $this->repository->getAll();

                return response()->json(['users' => MemberResource::collection($users), 'message' => 'Membro adicionado á sua organização com sucesso'], 201);
            }

            throw new \Exception('Falha ao criar membro da organização');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao registrar membro da organização: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->service->updateMember($request);

            if ($user) {
                DB::commit();

                $users = $this->repository->getAll();

                return response()->json(['users' => MemberResource::collection($users), 'message' => 'Dados do membro foram atualizados com sucesso'], 200);
            }

            throw new \Exception('Falha ao atualizar dados do membro');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar dados do membro: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function active(Request $request)
    {
        try {
            DB::beginTransaction();
            $member = $this->repository->update($request->input('userId'), ['active' => $request->input('active')]);

            if ($member) {
                DB::commit();

                $users = $this->repository->getAll();

                $message = $request->input('active') == 0 ? 'Usuário inativado com sucesso' : 'Usuário ativado com sucesso';

                return response()->json(['users' => MemberResource::collection($users), 'message' => $message], 200);
            }

            throw new \Exception('Falha ao atualizar dados do membro');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar dados do membro: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $this->rule->delete($id);
            $member = $this->repository->delete($id);

            if ($member) {
                DB::commit();

                return response()->json(['message' => 'Membro deletado com sucesso'], 200);
            }

            throw new \Exception('Falha ao deletar membro');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar membro: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
