<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $server;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->server = factory('App\Server')->create();
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanSeeAllServersForProject()
    {
        $this->be($this->user);
        $response = $this->get($this->server->project->path().'/servers/');
        $response->assertStatus(200);
        $response->assertSee($this->server->name);
    }

    public function testAnUnauthenticatedUserCanNotSeeAllServersForProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get($this->server->project->path().'/servers/');
    }

    public function testAnAuthenticatedUserCanViewASingleServer()
    {
        $this->be($this->user);
        $response = $this->get($this->server->path());
        $response->assertStatus(200);
        $response->assertSee($this->server->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewASingleServer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get($this->server->path());
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
