<?php

namespace App\Repositories\External;

use App\Helpers\CouponHelper;
use App\Models\External\EnterpriseHasCouponExternal;

class EnterpriseHasCouponExternalRepository
{
    protected $model;

    public function __construct(EnterpriseHasCouponExternal $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        CouponHelper::replaceCoupon($data['enterprise_id'], $data['coupon_id']);

        return $this->model->create($data);
    }

    public function getCouponsByEnterprise($id)
    {
        return $this->model->where('enterprise_id', $id)
            ->with('coupons')
            ->get();
    }

    public function delete($id)
    {
        $coupon = $this->findById($id);
        if ($coupon) {
            return $coupon->delete();
        }

        return false;
    }
}
