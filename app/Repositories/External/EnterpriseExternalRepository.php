<?php

namespace App\Repositories\External;

use App\Models\External\EnterpriseExternal;
use Illuminate\Support\Facades\DB;

class EnterpriseExternalRepository
{
    protected $model;

    public function __construct(EnterpriseExternal $enterprise)
    {
        $this->model = $enterprise;
    }

    public function getAll()
    {
        return $this->model->with(['subscription', 'coupon'])
            ->orderBy('created_by', 'desc')
            ->get();
    }

    public function findByIdWithRelations($id)
    {
        return $this->model->with('subscription')->find($id);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByCpf($cpf)
    {
        return $this->model->where('cpf', $cpf)->first();
    }

    public function findByCnpj($cnpj)
    {
        return $this->model->where('cnpj', $cnpj)->first();
    }

    public function update($id, array $data)
    {
        $enterprise = $this->findById($id);
        if ($enterprise) {
            $enterprise->update($data);

            return $enterprise;
        }

        return null;
    }

    public function delete($id)
    {
        $enterprise = $this->findById($id);
        if ($enterprise) {

            $offices = DB::connection('external')->table('enterprises')->where('created_by', $enterprise->id)->get();

            foreach ($offices as $office) {
                DB::connection('external')->table('movements')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('schedulings')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('accounts')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('categories')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('users')->where('enterprise_id', $office->id)
                    ->whereNotNull('department_id')
                    ->update(['department_id' => null]);
                DB::connection('external')->table('departments')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('feedbacks')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('financial_movements')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('orders')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('registers')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('notifications')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('users')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('settings_counter')->where('enterprise_id', $office->id)->delete();
                DB::connection('external')->table('enterprises')->where('id', $office->id)->delete();
            }

            DB::connection('external')->table('movements')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('schedulings')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('accounts')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('categories')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('users')->where('enterprise_id', $id)
                ->whereNotNull('department_id')
                ->update(['department_id' => null]);
            DB::connection('external')->table('departments')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('feedbacks')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('financial_movements')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('orders')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('registers')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('notifications')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('users')->where('enterprise_id', $id)->delete();
            DB::connection('external')->table('settings_counter')->where('enterprise_id', $id)->delete();

            return $enterprise->delete();
        }

        return false;
    }
}
