<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Bill;
use App\Helpers\FactoryHelper;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Bill::class, function (Faker $faker) {

    $c = FactoryHelper::getOrCreate(\App\Customer::class)->id;
    return [
        //
        'amount'=>$faker->numberBetween(1000,10000),
        'status'=>Arr::random(Bill::$Status),
        'creation_date'=>$faker->dateTimeBetween('-3 days','10 days'),
        'customer_id'  => $c,
    ];
});
