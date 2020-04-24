<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\Offer;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Offer::class, function (Faker $faker) {
    $p = FactoryHelper::fakeFile($faker,'offers/picture');
    $c = FactoryHelper::getOrCreate(\App\Category::class)->id;
    return [
        //
        'name'=>$faker->name,
        'picture'=>$p,
        'type'=>Arr::random(Offer::$Type),
        'status'=>Arr::random(Offer::$Status),
        'amount'=>$faker->numberBetween(100,1000)*100,
        'description'=>$faker->text(),
        'category_id'=>$c
    ];
});
