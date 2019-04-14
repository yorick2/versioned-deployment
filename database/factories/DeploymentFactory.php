<?php

use Faker\Generator as Faker;

$factory->define(App\Deployment::class, function (Faker $faker) {
    return [
        'server_id' => function(){
            return factory('App\Server')->create()->id;
        },
        'user_id' => function(){
            return factory('App\User')->create()->id;
        },
        'commit' => $faker->password,
        'success' => $faker->boolean,
        'notes' => $faker->paragraph,
        'output' => $faker->paragraph
    ];
});