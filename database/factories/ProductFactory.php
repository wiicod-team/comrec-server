<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $p = FactoryHelper::fakeFile($faker,'products/picture');
    $c = FactoryHelper::getOrCreate(\App\Category::class)->id;
    return [
        //
        'name'=>$faker->name,
        'picture'=>$p,
        'bvs_id'=>$faker->uuid,
        'category_id'=>$c
    ];
});
