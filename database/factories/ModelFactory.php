<?php
use App\Models\User;
use App\Models\Message;

$factory->define(User::class, function ($faker) {
	// $faker->addProvider(new Faker\Provider\ru_RU\Text($faker));
	// $faker->addProvider(new Faker\Provider\ru_RU\Person($faker));

    $avatars = [
        'avatars/user1.png',
        'avatars/user2.png',
        'avatars/user3.png',
        'avatars/user4.png'
    ];

    return [
        'nickname' => $faker->userName,
        'email' => $faker->email,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'avatar' => $faker->randomElement($avatars),
        'password' => 'secret',
        'created_at' => $faker->dateTimeBetween('-1 years', 'now')
    ];
});



$factory->define(Message::class, function($faker) {
    return [
        'message' => $faker->text($faker->numberBetween(50, 200)),
        'unreaded' => $faker->numberBetween(0, 1),
        'created_at' => $faker->dateTimeBetween('-1 years', 'now')
    ];
});

// $factory->define(App\Comment::class, function ($faker) {
//     return [
//         'text' => $faker->text
//     ];
// });
