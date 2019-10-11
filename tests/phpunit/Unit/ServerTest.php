<?php

namespace Tests\phpunit\Unit;

use Tests\phpunit\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $server;
    protected $project;

    public function setup() : void
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User', $this->server->owner);
    }

    public function testItHasAUrlPath()
    {
        $this->assertEquals(
            route('ShowServer', ['server'=> $this->server, 'project' => $this->project], false),
            $this->server->path()
        );
    }

    public function testCanAddADeployment()
    {
        $this->server->executeDeployment([
            'user_id' => 1,
            'notes' => 'some notes',
            'commit' => '553c2077f0edc3d5dc5d17262f6aa498e69d6f8e'
        ]);
        $this->server->executeDeployment([
            'user_id' => 1,
            'notes' => 'some notes',
            'commit' => '7fd1a60b01f91b314f59955a4e4d4e80d8edf11d'
        ]);
        $this->assertCount(2, $this->server->deployments);
    }
}
