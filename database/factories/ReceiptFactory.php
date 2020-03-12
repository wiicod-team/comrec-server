<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Helpers\FactoryHelper;
use App\Receipt;
use Faker\Generator as Faker;

$factory->define(Receipt::class, function (Faker $faker) {
    $pd = FactoryHelper::fakeFile($faker,'receipts/pdf');
    $b = (FactoryHelper::getOrCreate(\App\Bill::class,true));
    $u = (FactoryHelper::getOrCreate(\App\User::class));

    return [
        //
        'pdf'=>$pd,
        'amount'=>$b->amount,
        'bill_id'=>$b->id,
        'user_id'=>$u->id,
        'note'=>$faker->text()
    ];
});
