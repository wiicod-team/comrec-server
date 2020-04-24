<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Arr;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'phone'=>$faker->phoneNumber,
        'email'=>$faker->email,
        'username' => $faker->userName,
        'status'=>Arr::random(\App\User::$Status),
        'bvs_id'=>$faker->uuid,
        'has_reset_password'=>$faker->boolean,
        'password' => 'secret',
        'remember_token' => str_random(10),
        'settings'=>[]
    ];
});
