<?php

namespace App\Repositories\Internal;

use App\Models\Internal\Coupon;
use Illuminate\Support\Facades\DB;

class CouponRepository
{
    protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    public function getAll()
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        $coupon = $this->findById($id);
        if ($coupon) {
            $coupon->update($data);

            return $coupon;
        }

        return null;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        $coupon = $this->findById($id);
        if ($coupon) {
            DB::connection('external')->table('enterprise_has_coupons')->where('id', $id)->delete();

            return $coupon->delete();
        }

        return false;
    }
}
