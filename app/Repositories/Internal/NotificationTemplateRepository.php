<?php

namespace App\Repositories\Internal;

use App\Models\Internal\NotificationTemplates;

class NotificationTemplateRepository
{
    protected $model;

    public function __construct(
        NotificationTemplates $NotificationTemplate
    )
    {
        $this->model = $NotificationTemplate;
    }

    public function getAll(){
        return $this->model->all();
    }

    public function findById($id){
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $notification = $this->findById($id);
        if($notification) {
            return $notification->update($data);
        }
    }

    public function delete($id)
    {
        $notification = $this->findById($id);
        if($notification) {
            return $notification->delete($id);
        }
    }
}
