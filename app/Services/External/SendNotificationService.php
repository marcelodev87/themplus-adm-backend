<?php

namespace App\Services\External;

use App\Repositories\External\NotificationExternalRepository;
use App\Rules\NotificationRule;

class SendNotificationService
{
    protected $rule;

    protected $notificationExternalRepository;

    public function __construct(
        NotificationRule $rule,
        NotificationExternalRepository $notificationExternalRepository
    )
    {
        $this->rule = $rule;
        $this->notificationExternalRepository = $notificationExternalRepository;
    }

        public function sendNotification($request)
    {
        $this->rule->sendNotification($request);
        foreach ($request->enterprisesId as $enterpriseId) {
            $this->notificationExternalRepository->create($enterpriseId, $request->title, $request->text);
        }
    }
}
