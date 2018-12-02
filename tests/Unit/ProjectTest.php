<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->project->owner);
    }

    public function testCanAddAServer(){
        $this->project->addServer([
            'name' => 'foo',
            'user_id' => 1,
            'deploy_host' => 'example.com',
            'deploy_location' => '/var/www',
            'deploy_user' => 'root',
            'deploy_password' => 'password',
        ]);
        $this->project->addServer([
            'name' => 'bar',
            'user_id' => 1,
            'deploy_host' => 'test.com',
            'deploy_port' => '22',
            'deploy_location' => '/var/www',
            'deploy_user' => 'root',
            'deploy_password' => 'password',
            'notes' => 'some notes'
        ]);
        $this->assertCount(2, $this->project->servers);
    }

    public function testItHasAUrlPath(){
        $this->assertEquals(route('ShowProject',['project'=> $this->project], false), $this->project->path());
    }

}
