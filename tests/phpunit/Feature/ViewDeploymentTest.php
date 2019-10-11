<?php

namespace Tests\Feature;

use Tests\phpunit\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewDeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $deployment;
    protected $user;

    public function setup() : void
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanSeeAllDeploymentsForServer()
    {
        $this->be($this->user);
        $response = $this->get(route('DeploymentsIndex', ['server'=>$this->server,'project'=> $this->project]));
        $response->assertStatus(200);
        $response->assertSee($this->deployment->name);
    }


    public function testAnUnauthenticatedUserCanNotSeeAllDeploymentsForServer()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('DeploymentsIndex', ['server'=>$this->server,'project'=> $this->project]));
    }

    public function testAnAuthenticatedUserCanViewASingleDeployment()
    {
        $this->be($this->user);
        $response = $this->get(route(
            'ShowDeployment',
            ['deployment'=>$this->deployment,'server'=>$this->server,'project'=> $this->project]
        ));
        $response->assertStatus(200);
        $response->assertSee($this->deployment->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewASingleDeployment()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route(
            'ShowDeployment',
            ['deployment'=>$this->deployment,'server'=>$this->server,'project'=> $this->project]
        ));
    }
}
