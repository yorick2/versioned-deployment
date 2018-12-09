<?php

use Faker\Generator as Faker;

$factory->define(App\Server::class, function (Faker $faker) {
    $name = $faker->unique()->word();
    return [
       'project_id' => function(){
           return factory('App\Project')->create()->id;
       },
       'user_id' => function(){
           return factory('App\User')->create()->id;
       },
       'name' => $name,
       'slug' => str_slug($name),
       'deploy_host' => '172.21.0.2',
       'deploy_port' => '22',
       'deploy_location' => '/var/www',
       'deploy_user' => 'test',
       'shared' => 'test/text.txt
                    test/test2.txt
                    pub/test.txt',
       'commands' => 'touch test.txt',
       'notes' => $faker->paragraph
   ];
});
