<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\FactoryHelper;
use App\Suggestion;
use Faker\Generator as Faker;

$factory->define(Suggestion::class, function (Faker $faker) {
    $u = (FactoryHelper::getOrCreate(\App\User::class))->id;

    return [
        //
        'title'=>$faker->name,
        'status'=>\Illuminate\Support\Arr::random(Suggestion::$Status),
        'description'=>$faker->text,
        'user_id'=>$u,
    ];
});
