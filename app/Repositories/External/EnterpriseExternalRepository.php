<?php

namespace App\Repositories\External;

use App\Models\External\EnterpriseExternal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EnterpriseExternalRepository
{
    protected $model;

    protected $enterpriseHasCouponExternalRepository;

    public function __construct(EnterpriseExternal $enterprise, EnterpriseHasCouponExternalRepository $enterpriseHasCouponExternalRepository)
    {
        $this->model = $enterprise;
        $this->enterpriseHasCouponExternalRepository = $enterpriseHasCouponExternalRepository;
    }

    public function getAll()
    {
        return $this->model->with(['subscription', 'coupons'])
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

    public function create($data)
    {
        return $this->model->create($data);
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

    public function setCoupon($enterpriseId, $couponId)
    {
        $data = [
            'enterprise_id' => $enterpriseId,
            'coupon_id' => $couponId,
        ];

        return $this->enterpriseHasCouponExternalRepository->create($data);
    }

    private function hasTable($name)
    {
        return Schema::connection('external')->hasTable($name);
    }

    public function deleteOffice($id)
    {
        return DB::transaction(function () use ($id) {
            $enterprise = $this->findById($id);
            if (! $enterprise) {
                return false;
            }

            $this->purgeEnterpriseData($id);

            return $enterprise->delete();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $enterprise = $this->findById($id);
            if (! $enterprise) {
                return false;
            }

            $offices = DB::connection('external')->table('enterprises')->where('created_by', $id)->get();
            foreach ($offices as $office) {
                $this->purgeEnterpriseData($office->id);
                DB::connection('external')->table('enterprises')->where('id', $office->id)->delete();
            }

            $this->purgeEnterpriseData($id);
            $this->purgeMasterOnlyData($id);

            return $enterprise->delete();
        });
    }

    private function purgeEnterpriseData($enterpriseId)
    {
        $db = DB::connection('external');

        foreach (['movements', 'schedulings', 'financial_movements_receipts'] as $table) {
            if (! $this->hasTable($table)) {
                continue;
            }

            $records = $db->table($table)
                ->where('enterprise_id', $enterpriseId)
                ->whereNotNull('receipt')
                ->get();

            foreach ($records as $r) {
                $path = str_replace(env('AWS_URL').'/', '', $r->receipt);
                Storage::disk('s3')->delete($path);
            }

            $db->table($table)->where('enterprise_id', $enterpriseId)->delete();
        }

        if ($this->hasTable('cells') && $this->hasTable('cell_members')) {
            $cellIds = $db->table('cells')->where('enterprise_id', $enterpriseId)->pluck('id');
            $db->table('cell_members')->whereIn('cell_id', $cellIds)->delete();
        }

        if ($this->hasTable('ministries') && $this->hasTable('ministry_members')) {
            $ids = $db->table('ministries')->where('enterprise_id', $enterpriseId)->pluck('id');
            $db->table('ministry_members')->whereIn('ministry_id', $ids)->delete();
        }

        if ($this->hasTable('roles') && $this->hasTable('member_role')) {
            $roleIds = $db->table('roles')->where('enterprise_id', $enterpriseId)->pluck('id');
            $db->table('member_role')->whereIn('role_id', $roleIds)->delete();
        }

        if ($this->hasTable('users')) {
            $db->table('users')
                ->where('enterprise_id', $enterpriseId)
                ->update(['department_id' => null]);
        }

        $tables = [
            'accounts', 'categories', 'departments', 'feedbacks',
            'financial_movements', 'orders', 'registers', 'notifications',
            'settings_counter', 'roles', 'networks', 'cells', 'ministries',
            'users', 'members',
        ];

        foreach ($tables as $table) {
            if ($this->hasTable($table)) {
                $db->table($table)->where('enterprise_id', $enterpriseId)->delete();
            }
        }
    }

    private function purgeMasterOnlyData($enterpriseId)
    {
        $db = DB::connection('external');

        if (! $this->hasTable('pre_registrations')) {
            return;
        }

        $ids = $db->table('pre_registrations')
            ->where('enterprise_id', $enterpriseId)
            ->pluck('id');

        if ($this->hasTable('pre_registration_relationship')) {
            $db->table('pre_registration_relationship')
                ->whereIn('pre_registration_id', $ids)
                ->delete();
        }

        $db->table('pre_registrations')
            ->where('enterprise_id', $enterpriseId)
            ->delete();

        if ($this->hasTable('pre_registration_config')) {
            $db->table('pre_registration_config')
                ->where('enterprise_id', $enterpriseId)
                ->delete();
        }
    }

    public function destroyCouponByEnterprise($id)
    {
        $deletedRows = DB::connection('external')->table('enterprise_has_coupons')
            ->where('id', $id)
            ->delete();

        return $deletedRows > 0;
    }
}
