<?php

namespace App\Http\Controllers;

use App\Http\Resources\Internal\Service\ServiceSelectResource;
use App\Repositories\Internal\ServiceRepository;
use Illuminate\Support\Facades\Log;


class ServiceController
{
    private $repository;

    private $rule;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {
            $services = $this->repository->getAll();

            return response()->json(['services' => ServiceSelectResource::collection($services)], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar todas aos serviços: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


}
