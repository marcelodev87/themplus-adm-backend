<?php

namespace App\Services\External;

use App\Repositories\External\FeedbackExternalRepository;

class FeedbackExternalService
{
    protected $feedbackRepository;

    public function __construct(FeedbackExternalRepository $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function index()
    {
        return $this->feedbackRepository->getAll();
    }
}
