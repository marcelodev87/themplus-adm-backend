<?php

namespace App\Http\Controllers;

use App\Repositories\Internal\SettingRepository;
use App\Rules\Internal\SettingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController
{
    private $repository;

    private $rule;

    public function __construct(SettingRepository $repository, SettingRule $rule)
    {
        $this->repository = $repository;
        $this->rule = $rule;
    }

    public function index()
    {
        try {
            $settings = $this->repository->getAll();

            return response()->json(['settings' => $settings], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar configurações: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->rule->update($request);
            
            $data = [
                'allow_feedback_saved' => $request->input('allow_feedback_saved')
            ];

            $setting = $this->repository->update('allow_feedback_saved', $data['allow_feedback_saved']);

            if($setting){
                
                DB::commit();
                $setting = $this->repository->getAll();

                return response()->json(['settings' => $setting, 'message' => "Configuração atualizada com sucesso"], 200);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configuração'.$e->getMessage());
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}