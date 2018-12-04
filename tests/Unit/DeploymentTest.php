<?php

namespace Tests\Unit;

use App\SshConnection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $deployment;
    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->deployment->owner);
    }

    public function testItHasAUrlPath(){
        $this->assertEquals(
            route(
                'ShowDeployment',
                ['deployment'=>$this->deployment, 'server'=> $this->server, 'project' => $this->project]
                , false
            ),
            $this->deployment->path()
        );
    }

    public function testCanCreateSshConnection()
    {

        $cmd = 'echo foo';
        $connection = new SshConnection([
            'host' => 'example.com',
            'user' => 'test',
            'password' => 'password1',
            'port' => 22
        ]);
        $connection->connect();
        $response = $connection->execute($cmd);
        $this->assertEquals($response,'foo');
    }
    public function testCanSendBashRequest(){}
    public function testBashRequestSuccessfullyRuns(){}
    public function testSharedFilesSynced(){}
    public function testCurrentSymlinksWork(){}
    public function testFailureDuringSshAccessReportedToOutput(){}
    public function testFailureDuringSshAccessReportedToSuccessField(){}
    public function testFailureDuringBashReportedToOutput(){}
    public function testFailureDuringBashReportedToSuccessField(){}
    public function testFailureWithPhpReportedToOutput(){}
    public function testFailureWithPhpReportedToSuccessField(){}
}
