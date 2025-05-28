<?php

namespace App\Repositories\External;

use App\Models\External\EnterpriseExternal;
use Illuminate\Support\Facades\DB;
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

    public function delete($id)
    {
        $enterprise = $this->findById($id);
        if ($enterprise) {

            $offices = DB::connection('external')->table('enterprises')->where('created_by', $enterprise->id)->get();

            foreach ($offices as $office) {

                $movements = DB::connection('external')->table('movements')
                    ->where('enterprise_id', $office->id)
                    ->get();
                foreach ($movements as $movement) {
                    if ($movement->receipt) {
                        $oldFilePath = str_replace(env('AWS_URL') . '/', '', $movement->receipt);
                        Storage::disk('s3')->delete($oldFilePath);
                    }
                }
                DB::connection('external')->table('movements')->where('enterprise_id', $office->id)->delete();

                $schedulings = DB::connection('external')->table('schedulings')
                    ->where('enterprise_id', $office->id)
                    ->get();
                foreach ($schedulings as $scheduling) {
                    if ($scheduling->receipt) {
                        $oldFilePath = str_replace(env('AWS_URL') . '/', '', $scheduling->receipt);
                        Storage::disk('s3')->delete($oldFilePath);
                    }
                }
                DB::connection('external')->table('schedulings')
                    ->where('enterprise_id', $office->id)
                    ->delete();
                
                $financial_receipts = DB::connection('external')->table('financial_movements_receipts')->where('enterprise_id', $office->id)->get();
                foreach ($financial_receipts as $fr) {
                    if ($fr->receipt) {
                        $oldFilePath = str_replace(env('AWS_URL').'/', '', $fr->receipt);
                        Storage::disk('s3')->delete($oldFilePath);
                    }
                }

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

            $movements = DB::connection('external')->table('movements')
                ->where('enterprise_id', $id)
                ->get();
            foreach ($movements as $movement) {
                if ($movement->receipt) {
                    $oldFilePath = str_replace(env('AWS_URL') . '/', '', $movement->receipt);
                    Storage::disk('s3')->delete($oldFilePath);
                }
            }
            DB::connection('external')->table('movements')->where('enterprise_id', $id)->delete();

            $schedulings = DB::connection('external')->table('schedulings')
                ->where('enterprise_id', $id)
                ->get();
            foreach ($schedulings as $scheduling) {
                if ($scheduling->receipt) {
                    $oldFilePath = str_replace(env('AWS_URL') . '/', '', $scheduling->receipt);
                    Storage::disk('s3')->delete($oldFilePath);
                }
            }
            DB::connection('external')->table('schedulings')
                ->where('enterprise_id', $id)
                ->delete();
            
            $financial_receipts = DB::connection('external')->table('financial_movements_receipts')->where('enterprise_id', $id)->get();
            foreach ($financial_receipts as $fr) {
                if ($fr->receipt) {
                    $oldFilePath = str_replace(env('AWS_URL').'/', '', $fr->receipt);
                    Storage::disk('s3')->delete($oldFilePath);
                }
            }
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

    public function destroyCouponByEnterprise($id)
    {
        $deletedRows = DB::connection('external')->table('enterprise_has_coupons')
            ->where('id', $id)
            ->delete();

        return $deletedRows > 0;
    }
}
