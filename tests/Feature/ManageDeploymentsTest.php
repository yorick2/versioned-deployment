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

//    public function testAnAuthenticatedUserCanCreateAServerForAProject()
//    {
//        $this->be($this->user);
//        $unsavedServer = factory('App\Server')->make();
//        $this->post(route('SubmitCreateServer',['project'=> $unsavedServer->project]), $unsavedServer->toArray());
//        $this->get(route('ServersIndex',['project'=> $unsavedServer->project]) )->assertSee($unsavedServer->name);
//    }
//
//    public function testAnUnAuthenticatedUserCanNotCreateAServerForAProject()
//    {
//        $this->expectException('Illuminate\Auth\AuthenticationException');
//        $this->post(route('SubmitCreateServer',['project'=> $this->project]), []);
//    }

}
