<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Customer;
use App\Helpers\FactoryHelper;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        //
        'name'=>$faker->company,
        'email'=>$faker->email,
        'pending_days'=>Arr::random([30,45,60]),
        'status'=>Arr::random(Customer::$Status),
        'sale_network'=>$faker->text(20),
        'bvs_id'=>$faker->uuid
    ];
});
