<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponHelper
{
    private static function checkLimit($couponId)
    {
        $coupon = DB::table('coupons')->where('id', $couponId)->first();

        if ($coupon->limit !== null) {

            $using = DB::connection('external')->table('enterprise_has_coupons')
                ->where('coupon_id', $coupon->id)
                ->count();

            if ($using >= $coupon->limit) {
                throw new \Exception('Este cupom já atingiu o limite de utilizações');
            }
        }
    }

    private static function checkDateExpiration($couponId)
    {
        $coupon = DB::table('coupons')->where('id', $couponId)->first();

        if ($coupon->date_expiration) {
            $expirationDate = Carbon::createFromFormat('d/m/Y', $coupon->date_expiration);

            if ($expirationDate->isPast()) {
                throw new \Exception('Este cupom já foi expirado');
            }
        }
    }

    private static function checkAlreadyHave($enterpriseId, $couponId)
    {
        $using = DB::connection('external')->table('enterprise_has_coupons')
            ->where('enterprise_id', $enterpriseId)
            ->where('coupon_id', $couponId)
            ->count();

        if ($using > 0) {
            throw new \Exception('Sua organização já utiliza esse cupom');
        }
    }

    private static function checkSubscription($enterpriseId)
    {
        $couponsSubscriptions = DB::table('coupons')
            ->where('type', 'subscription')
            ->select('id')
            ->get();

        $subscriptionIds = $couponsSubscriptions->pluck('id');

        $coupons = DB::connection('external')->table('enterprise_has_coupons')
            ->where('enterprise_id', $enterpriseId)
            ->whereIn('coupon_id', $subscriptionIds)
            ->get();

        if ($coupons->count() > 0) {
            $ids = $coupons->pluck('id');

            DB::connection('external')->table('enterprise_has_coupons')
                ->whereIn('id', $ids)
                ->delete();
        }
    }

    private static function checkService($enterpriseId, $coupon)
    {
        $coupons = DB::connection('external')->table('enterprise_has_coupons')
            ->where('enterprise_has_coupons.enterprise_id', $enterpriseId)
            ->where('enterprise_has_coupons.coupon_id', $coupon->id)
            ->select('enterprise_has_coupons.id')
            ->get();

        if ($coupons->count() > 0) {
            $ids = $coupons->pluck('id');

            DB::connection('external')->table('enterprise_has_coupons')
                ->whereIn('id', $ids)
                ->delete();
        }
    }

    public static function replaceCoupon($enterpriseId, $couponId)
    {
        $coupon = DB::table('coupons')->where('id', $couponId)->first();

        switch ($coupon->type) {
            case 'subscription':
                self::checkSubscription($enterpriseId);
                break;

            default:
                self::checkService($enterpriseId, $coupon);
                break;
        }
    }

    public static function validate($enterpriseId, $couponId)
    {
        self::checkLimit($couponId);
        self::checkDateExpiration($couponId);
        self::checkAlreadyHave($enterpriseId, $couponId);
    }
}
