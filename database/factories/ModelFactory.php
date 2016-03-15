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

$factory->define(App\User::class, function ($faker) {
	// $faker->addProvider(new Faker\Provider\ru_RU\Text($faker));
	// $faker->addProvider(new Faker\Provider\ru_RU\Person($faker));

    return [
        'nickname' => $faker->name,
        'email' => $faker->email,
        'password' => 'secret'
    ];
});

$factory->define(App\Comment::class, function ($faker) {
    return [
        'text' => $faker->text
    ];
});
