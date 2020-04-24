<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\Invoice;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Invoice::class, function (Faker $faker) {
    $u = FactoryHelper::getOrCreate(\App\User::class)->id;
    return [
        //
        'status'=>Arr::random(Invoice::$Status),
        'amount'=>0,
        'payment_method'=>Arr::random(['momo','om','yup']),
        'payment_number'=>$faker->phoneNumber,
        'user_id'=>$u
    ];
});
