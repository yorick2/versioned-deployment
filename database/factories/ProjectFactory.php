<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'repository' => 'git@github.com:w3c/csswg-test.git',
        'notes' => $faker->paragraph
    ];
});