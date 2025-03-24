<?php

namespace App\Services\External;

use App\Helpers\EnterpriseHelper;
use App\Repositories\External\EnterpriseExternalRepository;
use App\Rules\EnterpriseRule;

class EnterpriseService
{
    protected $rule;

    protected $repository;

    public function __construct(
        EnterpriseRule $rule,
        EnterpriseExternalRepository $repository,

    ) {
        $this->rule = $rule;
        $this->repository = $repository;
    }

    public function update($request)
    {
        $this->rule->update($request);

        $enterprise = $this->repository->findById($request->input('id'));
        if (($request->input('cpf') && $request->input('cpf') !== $enterprise->cpf) || $request->input('cnpj') && $request->input('cnpj') !== $enterprise->cnpj) {
            EnterpriseHelper::existsEnterpriseCpfOrCnpj($request);
        }

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
        ];

        return $this->repository->update($request->input('id'), $data);
    }

    public function updateCoupon($request)
    {
        $this->rule->updateCoupon($request);

        return $this->repository->update($request->input('enterpriseId'), ['coupon_id' => $request->input('couponId')]);
    }
}
