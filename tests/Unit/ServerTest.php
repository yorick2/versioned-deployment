<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $server;
    protected $project;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->server->owner);
    }

    public function testItHasAUrlPath(){
        $this->assertEquals(
            route('ShowServer',['server'=> $this->server, 'project' => $this->project], false),
            $this->server->path()
        );
    }
}
