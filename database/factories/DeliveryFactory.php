<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Delivery;
use App\Helpers\FactoryHelper;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Delivery::class, function (Faker $faker) {
    $n = $faker->boolean?$faker->text():null;
    $i = FactoryHelper::getOrCreate(\App\Invoice::class)->id;
    return [
        //
        'status'=>Arr::random(Delivery::$Status),
        'town'=>Arr::random(['douala','yaounde']),
        'disctrict'=>$faker->city,
        'road'=>$faker->address,
        'note'=>$n,
        'invoice_id'=>$i

    ];
});
