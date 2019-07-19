<?php

namespace Tests\Unit;

use App\Git;
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
    protected $connection;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
        $this->connection = new SshConnection([
            'deploy_host' => 'example.com',
            'deploy_user' => 'test',
            'deploy_port' => 22
        ]);
        $this->connection->connect();
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
        $response = $this->connection->execute('echo foo');
        $this->assertStringStartsWith('foo', $response['message']);
    }

//    public function testUpdateMirrorWorks(){}

    public function testGitCloneWorks()
    {
        $git = new Git(
            $this->connection,
            $this->server
        );
        $deployment_one = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e',
        ]);
        $git->deploy($deployment_one);
        $response = $this->connection->execute("cd {$git->getCurrentReleaseLocation()} && git rev-parse HEAD");
        $this->assertStringStartsWith('553c2077f0edc3d5dc5d17262f6aa498e69d6f8e', $response['message']);
        $git_two = new Git(
            $this->connection,
            $this->server
        );
        $deployment_two = factory('App\Deployment')->create([
            'server_id' => $this->server->id,
            'commit' => '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d',
        ]);
        $git_two->deploy($deployment_two);
        $response = $this->connection->execute("cd {$git_two->getCurrentReleaseLocation()} && git rev-parse HEAD");
        $this->assertStringStartsWith('7fd1a60b01f91b314f59955a4e4d4e80d8edf11d', $response['message']);
    }

    public function testPreDeploymentCustomCommandsWorks(){
        $this->assertEquals(1, $this->connection->execute("cd {$this->server->deploy_location}; ".$this->server->pre_deploy_commands)['success']);
        $this->assertStringStartsWith(
            '/var/www/placeholder.txt',
            $this->connection->execute("ls  {$this->server->deploy_location}/placeholder.txt")['message']
        );
    }

//    public function testPostDeploymentCustomCommandsWorks(){}
//    public function testSharedFilesSynced(){}
//    public function testCurrentSymlinksWork(){}
//    public function testPreviousSymlinksWork(){}
}
