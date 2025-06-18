<?php

namespace App\Http\Controllers;

use App\Services\External\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController
{
    private $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function sendNotification(Request $request)
    {
        try {
            $this->service->sendNotification($request);

            return response()->json(['message' => 'Notificações enviadas com sucesso'], 200);

        } catch (\Exception $e) {

            Log::error('Erro ao enviar notificação: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
