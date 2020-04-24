<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\OfferProductUnit;
use Faker\Generator as Faker;

$factory->define(OfferProductUnit::class, function (Faker $faker) {
    $o = FactoryHelper::getOrCreate(\App\Offer::class)->id;
    $p = FactoryHelper::getOrCreate(\App\ProductUnit::class)->id;
    return [
        //
        'offer_id'  => $o,
        'product_unit_id'  => $p,
    ];
});
