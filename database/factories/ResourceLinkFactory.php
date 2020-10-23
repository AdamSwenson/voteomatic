<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Activity;
use App\LTIConsumer;
use App\Model;
use App\ResourceLink;
use Faker\Generator as Faker;

$factory->define(ResourceLink::class, function (Faker $faker) {

    //create a new activity and link it via the id
    $assignment = factory(Activity::class)->create();
    $consumer = factory(LTIConsumer::class)->create();
    return [
        'assignment_id' => $assignment->id,
        'description' => $faker->sentence,

        'lti_consumer_id' => $consumer->id


    ];
});
