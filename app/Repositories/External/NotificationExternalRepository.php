<?php

namespace App\Repositories\External;

use App\Models\External\NotificationExternal;

class NotificationExternalRepository
{
    protected $model;

    protected $userExternalRepository;

    public function __construct(NotificationExternal $notification, UserExternalRepository $userExternalRepository)
    {
        $this->model = $notification;
        $this->userExternalRepository = $userExternalRepository;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function create($entepriseId, $title, $text)
    {
        $users = $this->userExternalRepository->getMembersByEnterprise($entepriseId);

        foreach ($users as $user) {
            $data = [
                'user_id' => $user->id,
                'enterprise_id' => $entepriseId,
                'title' => $title,
                'text' => $text,
            ];

            $this->model->create($data);
        }
    }

    public function createForUser($data)
    {
        return $this->model->create($data);
    }
}
