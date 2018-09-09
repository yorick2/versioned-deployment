<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $deployment;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->deployment = factory('App\Deployment')->create();
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanSeeAllDeploymentsForServer()
    {
        $this->be($this->user);
        $response = $this->get($this->deployment->server()->first()->path());
        $response->assertStatus(200);
        $response->assertSee($this->deployment->name);
    }

    public function testAnAuthenticatedUserCanViewASingleDeployment()
    {
        $this->be($this->user);
        $response = $this->get( $this->deployment->path());
        $response->assertStatus(200);
        $response->assertSee($this->deployment->Name);
    }

    public function testAnUnauthenticatedUserCanNotSeeAllDeploymentsForServer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get($this->deployment->server()->first()->path());
    }

    public function testAnUnauthenticatedUserCanNotViewASingleDeployment()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get( $this->deployment->path());
    }





}
