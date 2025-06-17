<?php

namespace App\Repositories;

use App\Models\External\NotificationExternal;
use App\Repositories\External\UserExternalRepository;

class NotificationExternalRepository
{
    protected $model;

    protected $userRepository;

    public function __construct(NotificationExternal $notification, UserExternalRepository $userRepository)
    {
        $this->model = $notification;
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function create($entepriseId, $title, $text)
    {
        $users = $this->userRepository->getAllByEnterprise($entepriseId);

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
