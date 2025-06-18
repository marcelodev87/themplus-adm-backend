<?php

namespace App\Http\Controllers;

use App\Services\External\SendNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendNotificationController
{
    private $service;

    public function __construct(SendNotificationService $service)
    {
        $this->service = $service;
    }

        public function sendNotification(Request $request)
    {
        try {
            $this->service->sendNotification($request);

            return response()->json(['message' => 'Notificações enviadas com sucesso'], 200);

        } catch (\Exception $e) {

            Log::error('Erro ao envioar notificação: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
