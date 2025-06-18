<?php

namespace App\Http\Controllers;

use App\Http\Resources\Internal\TemplateNotificationResource;
use App\Repositories\Internal\NotificationTemplateRepository;
use App\Rules\Internal\TemplateRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationTemplateController
{
    private $repository;

    private $rule;

    public function __construct(NotificationTemplateRepository $repository, TemplateRule $rule)
    {
        $this->repository = $repository;
        $this->rule = $rule;
    }

    public function index()
    {
        try {
            $templates = $this->repository->getAll();

            return response()->json([
                'templates' => TemplateNotificationResource::collection($templates),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todos os templates: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->rule->create($request);

            $template = $this->repository->create($request->only(['title', 'text']));

            if ($template) {
                DB::commit();

                $templates = $this->repository->getAll();

                return response()->json(['templates' => $templates, 'message' => 'Template criado com sucesso'], 201);
            }
            throw new \Exception('Falha ao criar template');
        } catch (\Exception $e) {
            Log::error('Erro ao criar template: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->rule->update($request);

            $template = $this->repository->update($request->id, $request->only(['title', 'text']));

            if ($template) {

                DB::commit();
                $templates = $this->repository->getAll();

                return response()->json(['templates' => $templates, 'message' => 'Template atualizado com sucesso'], 200);

            }
            throw new \Exception('Falha ao atualizar template');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar template: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->rule->delete($request->id);

            $template = $this->repository->delete($request->id);
            if ($template) {
                DB::commit();

                return response()->json(['message' => 'Template deletado com sucesso'], 200);
            }
            throw new \Exception('Falha ao deletar template');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar template: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
