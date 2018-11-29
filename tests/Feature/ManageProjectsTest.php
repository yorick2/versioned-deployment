<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class manageProjectsTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->user = factory('App\User')->create();
    }

    public function testAnAuthenticatedUserCanCreateAProject()
    {
        $this->be($this->user);
        $unsavedProject = factory('App\Project')->make();
        $this->post('/create-project', $unsavedProject->toArray());
        $this->get('projects')->assertSee($unsavedProject->name);
    }

    public function testAnUnAuthenticatedUserCanNotCreateAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/create-project', []);
    }

    public function testAnAuthenticatedUserCanEditAProject()
    {
        $this->be($this->user);
        $project = factory('App\Project')->create();
        $this->patch($project->path(),[
            'name' => 'edited name',
            'repository' => 'git@github.com:w3c/csswg-test.git',
            'notes' => 'I was edited'
        ]);
        $project = $project->fresh(); # refresh from database
        $this->assertEquals('edited name',$project->name);
        $this->assertEquals('git@github.com:w3c/csswg-test.git',$project->repository);
        $this->assertEquals('I was edited',$project->notes);
    }

    public function testAnUnAuthenticatedUserCanNotEditAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $server = factory('App\Project')->create();
        $this->patch($server->path(),[]);
    }

    public function testAnAuthenticatedUserCanDeleteAProject()
    {
        $this->be($this->user);
        $project = factory('App\Project')->create();
        $servers = factory('App\Server', 3)->create(['project_id' => $project->id]);
        $serverId = $servers->first()->id;
        factory('App\Deployment', 3)->create(['server_id' => $serverId]);
        $response = $this->json('DELETE', $project->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects',['id' => $project->id]);
        $this->assertDatabaseMissing('servers',['project_id' => $project->id]);
        $this->assertDatabaseMissing('deployments',['server_id' => $serverId]);
    }

    public function testAnUnAuthenticatedUserCanNotDeleteAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $project = factory('App\Project')->create();
        $response = $this->json('DELETE', $project->path());
        $response->assertRedirect('/login');
    }
}

