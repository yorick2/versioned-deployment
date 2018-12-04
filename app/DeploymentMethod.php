<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMethod extends Model
{
    protected $guarded = [];

    /**
     * @return array
     */
    public function execute($deployment)
    {
        $cmd = 'echo foo';
        $serverData = $deployment->server->toArray();
        $connection = new SshConnection($serverData);
//        $connection = new SshConnection([
//            'host' => 'example.com',
//            'user' => 'test',
//            'password' => 'password1',
//            'port' => 22
//        ]);
        $connection->connect();
        $response = $connection->execute($cmd);

//
//        $output = '';
//        return [
//            'output'=>$output,
//            'success'=>True
//        ];
    }



}
