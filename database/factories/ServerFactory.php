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
       'deploy_host' => 'example.com',
       'deploy_port' => '22',
       'deploy_location' => '/var/www',
       'deploy_user' => 'test',
       'deploy_branch' => 'master',
       'shared_files' => 'test/testFolder, test/testFolder2, pub/testFolder, test/test.txt, test/test2.txt, pub/test.txt',
       'pre_deploy_commands' => 'touch placeholder.txt',
       'post_deploy_commands' => 'rm placeholder.txt',
       'notes' => $faker->paragraph
   ];
});
