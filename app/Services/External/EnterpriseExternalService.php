<?php

namespace App\Services\External;

use App\Helpers\CategoryHelper;
use App\Helpers\CouponHelper;
use App\Helpers\EnterpriseHelper;
use App\Repositories\External\AccountExternalRepository;
use App\Repositories\External\EnterpriseExternalRepository;
use App\Repositories\External\SettingsCounterExternalRepository;
use App\Repositories\External\SubscriptionExternalRepository;
use App\Repositories\External\UserExternalRepository;
use App\Rules\EnterpriseRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class EnterpriseExternalService
{
    protected $rule;

    protected $repository;

    protected $subscriptionExternalRepository;

    protected $accountExternalRepository;

    protected $settingsCounterExternalRepository;

    protected $userExternalRepository;

    public function __construct(
        EnterpriseRule $rule,
        EnterpriseExternalRepository $repository,
        SubscriptionExternalRepository $subscriptionExternalRepository,
        AccountExternalRepository $accountExternalRepository,
        SettingsCounterExternalRepository $settingsCounterExternalRepository,
        UserExternalRepository $userExternalRepository,

    ) {
        $this->rule = $rule;
        $this->repository = $repository;
        $this->subscriptionExternalRepository = $subscriptionExternalRepository;
        $this->accountExternalRepository = $accountExternalRepository;
        $this->settingsCounterExternalRepository = $settingsCounterExternalRepository;
        $this->userExternalRepository = $userExternalRepository;

    }

    private function createEnterprise($request)
    {
        $expired = $request->input('subscriptionDateExpired');

        $data = [
            'name' => $request->input('enterprise.name'),
            'email' => $request->input('enterprise.email'),
            'cep' => $request->input('enterprise.cep'),
            'state' => $request->input('enterprise.state'),
            'city' => $request->input('enterprise.city'),
            'neighborhood' => $request->input('enterprise.neighborhood'),
            'address' => $request->input('enterprise.address'),
            'number_address' => $request->input('enterprise.numberAddress'),
            'complement' => $request->input('enterprise.complement'),
            'phone' => $request->input('enterprise.phone'),
            'subscription_id' => $request->input('subscription'),
            'expired_date' => Carbon::createFromFormat(
                'd/m/Y',
                $expired
            )->format('Y-m-d'),
            'cnpj' => $request->input('enterprise.cnpj'),
            'cpf' => $request->input('enterprise.cpf'),
        ];

        return $this->repository->create($data);
    }

    private function createUser($enterpriseId, $request)
    {

        $data = [
            'name' => $request->input('user.name'),
            'email' => $request->input('user.email'),
            'position' => 'admin',
            'enterprise_id' => $enterpriseId,
            'view_enterprise_id' => $enterpriseId,
            'password' => Hash::make($request->input('user.password')),

        ];

        return $this->userExternalRepository->create($data);
    }

    private function createAccount($id)
    {
        $data = ['name' => 'Caixinha', 'enterprise_id' => $id];
        $this->accountExternalRepository->create($data);
    }

    private function createSettingsCounter($id)
    {
        $this->settingsCounterExternalRepository->create(['enterprise_id' => $id]);
    }

    public function create($request)
    {
        $enterprise = $this->createEnterprise($request);

        if ($request->input('enterprise.position') == 'client') {
            $this->createAccount($enterprise->id);
            $this->createSettingsCounter($enterprise->id);
            CategoryHelper::createDefault($enterprise->id);
        }

        $this->createUser($enterprise->id, $request);

        return $enterprise;
    }

    public function update($request)
    {
        $enterprise = $this->repository->findById($request->input('id'));

        if (($request->input('cpf') && $request->input('cpf') !== $enterprise->cpf) || $request->input('cnpj') && $request->input('cnpj') !== $enterprise->cnpj) {
            EnterpriseHelper::existsEnterpriseCpfOrCnpj($request);
        }

        $expired = $request->input('subscriptionDateExpired');

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cnpj' => $request->input('cnpj'),
            'cpf' => $request->input('cpf'),
            'cep' => $request->input('cep'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'neighborhood' => $request->input('neighborhood'),
            'address' => $request->input('address'),
            'number_address' => $request->input('number_address'),
            'complement' => $request->input('complement'),
            'phone' => $request->input('phone'),
            'code_financial' => $request->input('code_financial'),
            'subscription_id' => $request->input('subscription'),
            'expired_date' => Carbon::createFromFormat(
                'd/m/Y',
                $expired
            )->format('Y-m-d'),
        ];

        return $this->repository->update($request->input('id'), $data);
    }

    public function setCoupon($request)
    {
        $this->rule->setCoupon($request);
        CouponHelper::validate($request->input('enterpriseId'), $request->input('couponId'));

        return $this->repository->setCoupon($request->input('enterpriseId'), $request->input('couponId'));
    }
}
