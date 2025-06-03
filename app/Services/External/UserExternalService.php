<?php

namespace App\Services\External;

use App\Repositories\External\UserExternalRepository;
use App\Rules\External\UserExternalRule;

class UserExternalService
{
    protected $userExternalRepository;

    protected $userRuleExternal;

    public function __construct(
        UserExternalRule $userRuleExternal,
        UserExternalRepository $userExternalRepository
    ) {
        $this->userExternalRepository = $userExternalRepository;
        $this->userRuleExternal = $userRuleExternal;
    }

    public function updateMemberUser($request)
    {
        $this->userRuleExternal->updateMemberUser($request);

        $data = $request->only(['name', 'email', 'position', 'phone', 'active']);

        return $this->userExternalRepository->updateMemberUser($request->id, $data);
    }
}
