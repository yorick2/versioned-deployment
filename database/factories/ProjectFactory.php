<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'user_id' => function(){
            return factory('App\User')->create()->id;
        },
        'repository' => 'git@github.com:w3c/csswg-test.git',
        'notes' => $faker->paragraph
    ];
});