<?php

namespace Tests\Feature;

use App\Deployment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class manageDeploymentsTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $deployment;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->deployment = factory('App\Deployment')->create(['server_id'=>$this->server->id]);
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanRunADeployment()
    {
        $this->be($this->user);
        $unsavedDeployment = factory('App\Deployment')->make(['server_id'=>$this->server->id]);
        $this->post(route('SubmitCreateDeployment',['project'=> $this->project,'server'=> $this->server]), $unsavedDeployment->toArray());
        $this->get(route('DeploymentsIndex',['project'=> $this->project,'server'=> $this->server]) )
            ->assertSee($unsavedDeployment->name);
    }

    public function testAnUnAuthenticatedUserCanNotRunADeployment()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post(route('SubmitCreateDeployment',['project'=> $this->project,'server'=> $this->server]), []);
    }

}
