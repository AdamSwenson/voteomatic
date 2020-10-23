<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LTIConsumer;
use Faker\Generator as Faker;

$factory->define(LTIConsumer::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->company,
        'consumer_key' => $faker->sha1,
        'secret_key' => $faker->sha1,
    ];
});
