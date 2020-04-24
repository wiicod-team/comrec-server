<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\Product;
use App\ProductUnit;
use Faker\Generator as Faker;

$factory->define(ProductUnit::class, function (Faker $faker) {
    $p = FactoryHelper::getOrCreate(Product::class);
    $pa = ["B","C6","C12"];
    return [
        'unit' => $pa[random_int(0,count($pa)-1)],
        'product_id'  => $p->id,
        'quantity'=>$faker->numberBetween(10,100),
        'amount'=>$faker->numberBetween(100,1000)*1000
    ];
});
