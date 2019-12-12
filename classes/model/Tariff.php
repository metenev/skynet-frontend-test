<?php

namespace SkyNetFront\Model;

use SkyNetFront\Core\Model;
use SkyNetFront\Core\Helper\Url;
use SkyNetFront\Model\Plan;

class Tariff extends Model {

    const COLORS = [
        1 => '#70603e',
        2 => '#0075d9',
        3 => '#e74807',
        4 => '#0075d9',
        5 => '#e74807',
    ];

    public static function create($id, array $data)
    {
        $tariff = [
            'id' => intval($id),
            'title' => $data['title'],
            'url' => Url::get("/index/tariff/{$id}"),
            'help_link' => $data['link'],
            'help_label' => _('find out more at www.sknt.ru'),
            'speed' => intval($data['speed']),
            'speed_label' => sprintf( _('%d Mbit/s'), intval($data['speed']) ),
            'color' => self::COLORS[ $id ],
            'free_options' => isset($data['free_options']) ? $data['free_options'] : [],
        ];

        $priceForUnit = 0;

        // Get price for 1 month

        foreach ($data['tarifs'] as $idx => $item)
        {
            $price = floatval($item['price']);

            if ($item['pay_period'] == 1)
            {
                $priceForUnit = $price;
            }
        }

        $priceRange[] = [999999, 0];
        $plans = [];

        foreach ($data['tarifs'] as $idx => $item)
        {
            $priceTotal = floatval($item['price']);
            $priceMonthly = $priceTotal / $item['pay_period'];

            // Calculate lowest and highest price
            if ($priceMonthly < $priceRange[0]) $priceRange[0] = $priceMonthly;
            else if ($priceMonthly > $priceRange[1]) $priceRange[1] = $priceMonthly;

            // Build model

            $item['price_monthly'] = $priceMonthly;
            $item['one_payment_discount'] = ($priceForUnit * $item['pay_period']) - $priceTotal;

            $plan = Plan::create($item);
            $planId = $plan->field('id');

            $plans[ $planId ] = $plan;
        }

        $tariff['plans'] = $plans;

        $tariff['price_range_label'] = implode(' &ndash; ', $priceRange) . ' ₽/мес';

        $model = new Tariff();
        $model->data = $tariff;

        return $model;
    }

    public static function processLoadedData(array $data)
    {
        $models = [];
        $plansToModels = [];

        foreach ($data as $idx => $item)
        {
            $model = self::create($idx + 1, $item);
            $models[] = $model;

            foreach ($model->field('plans') as $plan)
            {
                $planId = $plan->field('id');
                $plansToModels[ $planId ] = $model;
            }
        }

        return [
            'tariffs' => $models,
            'plansToTariffs' => $plansToModels,
        ];
    }

    public static function sortPlans($a, $b)
    {
        $p1 = $a->field('pay_period');
        $p2 = $b->field('pay_period');

        if ($p1 == $p2)
        {
            return 0;
        }

        return ($p1 < $p2) ? -1 : 1;
    }

    public function getSortedPlans()
    {
        $data = array_values($this->data['plans']);

        usort($data, '\SkyNetFront\Model\Tariff::sortPlans');

        return $data;
    }
    

}
