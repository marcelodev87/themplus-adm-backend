<?php

namespace App\Repositories\External;

use App\Models\External\FeedbackSavedExternal;

class FeedbackSavedExternalRepository
{
    protected $model;

    public function __construct(FeedbackSavedExternal $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->orderByDesc('date_feedback')->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        $feedback = $this->findById($id);
        if ($feedback) {
            return $feedback->delete();
        }

        return false;
    }
}
