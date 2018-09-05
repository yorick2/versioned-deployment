<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $deployment;

    public function setup()
    {
        parent::setUp();
        $this->deployment = factory('App\Deployment')->create();
    }

    public function testAUserCanSeeAllDeployekmtsForServer()
    {
        $response = $this->get($this->deployment->server()->first()->path());
        $response->assertStatus(200);
        $response->assertSee($this->deployment->name);
    }

    public function testAUserCanViewASingleDeployment()
    {
        $response = $this->get( $this->deployment->path());
        $response->assertStatus(200);
        $response->assertSee($this->deployment->Name);
    }





}
