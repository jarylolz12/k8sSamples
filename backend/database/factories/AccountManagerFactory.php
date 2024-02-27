<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AccountManager;
use Faker\Generator as Faker;

$factory->define(AccountManager::class, function (Faker $faker) {
    return [
        'customer_id' => rand(1, 20),
        'user_id'   => rand(1, 5)
    ];
});
