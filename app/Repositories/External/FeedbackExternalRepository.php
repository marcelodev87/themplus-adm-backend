<?php

namespace App\Repositories\External;

use App\Models\External\FeedbackExternal;

class FeedbackExternalRepository
{
    protected $model;

    public function __construct(FeedbackExternal $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['externalUser', 'externalEnterprise'])->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
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
