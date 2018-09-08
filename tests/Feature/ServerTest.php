<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $server;

    public function setup()
    {
        parent::setUp();
        $this->server = factory('App\Server')->create();
    }

    public function testAUserCanSeeAllServersForProject()
    {
        $response = $this->get($this->server->project->path().'/servers/');
        $response->assertStatus(200);
        $response->assertSee($this->server->name);
    }

    public function testAUserCanViewASingleServer()
    {
        $response = $this->get($this->server->path());
        $response->assertStatus(200);
        $response->assertSee($this->server->Name);
    }





}
