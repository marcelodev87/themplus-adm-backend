<?php

namespace App\Helpers;

use App\Models\External\EnterpriseExternal;
use App\Repositories\External\EnterpriseExternalRepository;

class EnterpriseHelper
{
    public static function existsEnterpriseCpfOrCnpj($request)
    {
        $enterpriseRepository = new EnterpriseExternalRepository(new EnterpriseExternal);

        if ($request->input('cpf') !== null) {
            $enterprise = $enterpriseRepository->findByCpf($request->input('cpf'));
            if ($enterprise && $enterprise->id !== $request->user()->enterprise_id) {
                throw new \Exception('O CPF já está em uso por outra conta');
            }
        }
        if ($request->input('cnpj') !== null) {
            $enterprise = $enterpriseRepository->findByCnpj($request->input('cnpj'));
            if ($enterprise && $enterprise->id !== $request->user()->enterprise_id) {
                throw new \Exception('O CNPJ já está em uso por outra conta');
            }
        }
    }
}
