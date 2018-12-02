<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanSeeAllServersForProject()
    {
        $this->be($this->user);
        $response = $this->get(route('ServersIndex',['project'=> $this->project]));
        $response->assertStatus(200);
        $response->assertSee($this->server->name);
    }

    public function testAnUnauthenticatedUserCanNotSeeAllServersForProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('ServersIndex',['project'=> $this->project]));
    }

    public function testAnAuthenticatedUserCanViewASingleServer()
    {
        $this->be($this->user);
        $response = $this->get(route('ShowServer',['project'=> $this->project, 'server'=>$this->server]));
        $response->assertStatus(200);
        $response->assertSee($this->server->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewASingleServer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('ShowServer',['project'=> $this->project, 'server'=>$this->server]));
    }

    public function testAnAuthenticatedUserCanViewServerCreatePage()
    {
        $this->be($this->user);
        $response = $this->get(route('CreateServer',['project'=> $this->server->project]));
        $response->assertStatus(200);
        $response->assertSee($this->server->project->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewServerCreatePage()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('CreateServer',['project'=> $this->server->project]));
    }

}
