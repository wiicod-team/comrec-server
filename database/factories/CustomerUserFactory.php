<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\CustomerUser;
use App\Helpers\FactoryHelper;
use Faker\Generator as Faker;

$factory->define(CustomerUser::class, function (Faker $faker) {
    $c = FactoryHelper::getOrCreate(\App\Customer::class)->id;
    $u = FactoryHelper::getOrCreate(\App\User::class)->id;
    return [
        //
       'customer_id'  => $c,
       'user_id'  => $u,
    ];
});
