<?php

use Faker\Generator as Faker;

$factory->define(App\Deployment::class, function (Faker $faker) {
    return [
        'server_id' => function () {
            return factory('App\Server')->create()->id;
        },
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'commit' => '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d',
        'success' => $faker->boolean,
        'notes' => $faker->paragraph,
        'output' => '[
   {
      "name":"test",
      "success":1,
      "message":"success"
   },
   {
      "name":"another test",
      "success":1,
      "message":"success"
   }
]'
    ];
});
