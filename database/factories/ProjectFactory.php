<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    $name = $faker->unique()->company;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'user_id' => function(){
            return factory('App\User')->create()->id;
        },
        'repository' => 'https://github.com/octocat/Hello-World',
        'notes' => $faker->paragraph
    ];
});
