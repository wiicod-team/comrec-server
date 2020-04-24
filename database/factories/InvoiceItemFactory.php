<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\InvoiceItem;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(InvoiceItem::class, function (Faker $faker) {

    $cc=Arr::random(InvoiceItem::$Concerns);
    $c = FactoryHelper::getOrCreate($cc)->id;
    $i = FactoryHelper::getOrCreate(\App\InvoiceItem::class)->id;
    return [
        //
        'price_was'=>0,
        'quantity'=>$faker->numberBetween(1,10),
        'concern_id'=>$c,
        'concern_type'=>$cc,
        'invoice_id'=>$i
    ];
});
