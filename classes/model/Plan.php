<?php

namespace SkyNetFront\Model;

use SkyNetFront\Core\Model;
use SkyNetFront\Core\Helper\Url;

class Plan extends Model {

    public static function create(array $data)
    {
        $id = intval($data['ID']);
        $payPeriod = intval($data['pay_period']);
        $price = floatval($data['price']);

        $plan = [
            'id' => $id,
            'title' => sprintf( ngettext('%d month', '%d months', $payPeriod), $payPeriod ),
            'url' => Url::get("/index/plan/{$id}"),
            'pay_period' => $payPeriod,
            'price' => $price,
            'price_label' => sprintf( _('Single payment &ndash; $%d'), $price ),
            'price_monthly' => $data['price_monthly'],
            'price_monthly_label' => sprintf( _('$%d/month'), $price / $payPeriod ),
            'one_payment_discount' => $data['one_payment_discount'],
            'one_payment_discount_label' => sprintf( _( 'profit &ndash; $%d'), $data['one_payment_discount'] ),
            'withdraw_amount_label' => sprintf( _('to debit &ndash; $%d'), $price ),
            'new_payday' => $data['new_payday'],
        ];

        $model = new Plan();
        $model->data = $plan;

        return $model;
    }

}
