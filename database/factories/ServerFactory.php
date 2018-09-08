<?php

use Faker\Generator as Faker;

$factory->define(App\Server::class, function (Faker $faker) {
   return [
       'project_id' => function(){
           return factory('App\Project')->create()->id;
       },
       'user_id' => function(){
           return factory('App\User')->create()->id;
       },
       'name' => $faker->unique()->word(),
       'deploy_host' => 'example.com',
       'deploy_location' => '/var/www',
       'deploy_user' => 'test',
       'deploy_password' => 'password',
       'notes' => $faker->paragraph
   ];
});
