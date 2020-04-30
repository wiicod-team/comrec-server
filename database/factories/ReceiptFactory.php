<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Helpers\FactoryHelper;
use App\Receipt;
use Faker\Generator as Faker;

$factory->define(Receipt::class, function (Faker $faker) {
    $b = (FactoryHelper::getOrCreate(\App\Bill::class,true));
    $u = (FactoryHelper::getOrCreate(\App\User::class));

    return [
        //
        'amount'=>$b->amount,
        'bill_id'=>$b->id,
        'user_id'=>$u->id,
        'payment_method'=>$faker->creditCardType,
        'note'=>$faker->text()
    ];
});
