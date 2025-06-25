<?php

namespace App\Http\Controllers;

use App\Http\Resources\External\Feedback\FeedbackSavedTableResource;
use App\Http\Resources\External\Feedback\FeedbackTableResource;
use App\Repositories\External\FeedbackExternalRepository;
use App\Repositories\External\FeedbackSavedExternalRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedbackController
{
    protected $feedbackRepository;

    protected $feedbackSavedRepository;

    public function __construct(FeedbackExternalRepository $feedbackRepository, FeedbackSavedExternalRepository $feedbackSavedRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->feedbackSavedRepository = $feedbackSavedRepository;
    }

    public function index()
    {
        try {
            $feedbacks = $this->feedbackRepository->getAll();

            return response()->json([
                'feedbacks' => FeedbackTableResource::collection($feedbacks),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar feedbacks'.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getSaved()
    {
        try {
            $savedFeedbacks = $this->feedbackSavedRepository->getAll();

            return response()->json([
                'feedbacks' => FeedbackSavedTableResource::collection($savedFeedbacks),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro buscar feedbacks'.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store($id)
    {
        try {
            DB::beginTransaction();
            $feedback = $this->feedbackRepository->findById($id);

            if ($feedback) {

                $feedback->load('externalUser', 'externalEnterprise');
                $data = [
                    'user_name' => $feedback->externalUser->name,
                    'user_email' => $feedback->externalUser->email,
                    'enterprise_name' => $feedback->externalEnterprise->name,
                    'message' => $feedback->message,
                    'date_feedback' => $feedback->created_at->format('Y-m-d'),
                ];

                $feedbackSaved = $this->feedbackSavedRepository->create($data);
                if ($feedbackSaved) {
                    DB::commit();
                    $this->feedbackRepository->delete($id);
                    $feedbacks = $this->feedbackRepository->getAll();

                    return response()->json(['feedbacks' => FeedbackTableResource::collection($feedbacks), 'message' => 'Feedback salvo com sucesso'], 201);
                }
            }
            throw new \Exception('Falha ao salvar feedback');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar feedback'.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteSaved($id)
    {
        try {
            DB::beginTransaction();
            $feedback = $this->feedbackSavedRepository->delete($id);

            if ($feedback) {
                DB::commit();

                $feedbacks = $this->feedbackSavedRepository->getAll();

                return response()->json(['feedbacks' => FeedbackSavedTableResource::collection($feedbacks), 'message' => 'Feedback deletado com sucesso'], 200);
            }
            throw new \Exception('Falha ao deletar feedback');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar feedback: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $feedback = $this->feedbackRepository->delete($id);

            if ($feedback) {
                DB::commit();

                $feedbacks = $this->feedbackRepository->getAll();

                return response()->json(['feedbacks' => FeedbackTableResource::collection($feedbacks), 'message' => 'Feedback deletado com sucesso'], 200);
            }
            throw new \Exception('Falha ao deletar feedback');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao deletar feedback: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
