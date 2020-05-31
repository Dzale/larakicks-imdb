<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'creator_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'text' => 'text',
        'rate' => '202',

    ];
});
