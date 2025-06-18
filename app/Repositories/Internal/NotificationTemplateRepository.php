<?php

namespace App\Repositories\Internal;

use App\Models\Internal\NotificationTemplate;

class NotificationTemplateRepository
{
    protected $model;

    public function __construct(
        NotificationTemplate $model
    ) {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $template = $this->findById($id);
        if ($template) {
            return $template->update($data);
        }
    }

    public function delete($id)
    {
        $template = $this->findById($id);
        if ($template) {
            return $template->delete($id);
        }
    }
}
