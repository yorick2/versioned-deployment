<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\phpunit\TestCase;

class ManageProjectsTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $user;

    public function setup() : void
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->user = factory('App\User')->create();
    }

    public function testItRequiresAUniqueSlug()
    {
        $this->be($this->user);
        $project = factory('App\Project')->create(['name' => 'foo bar', 'slug' => 'foo-bar']);
        $dataArray = $project->toArray();
        $this->assertEquals($project->fresh()->slug, 'foo-bar');
        $this->post(route('SubmitCreateProject'), $dataArray);
        $this->assertTrue(Project::whereSlug('foo-bar-2')->exists());
        $this->post(route('SubmitCreateProject'), $dataArray);
        $this->assertTrue(Project::whereSlug('foo-bar-3')->exists());
        factory('App\Project')->create(['name' => 'foo bar', 'slug' => 'foo-bar-10']);
        $this->post(route('SubmitCreateProject'), $dataArray);
        $this->assertTrue(Project::whereSlug('foo-bar-11')->exists());
    }

    public function testAnAuthenticatedUserCanCreateAProject()
    {
        $this->be($this->user);
        $unsavedProject = factory('App\Project')->make();
        $this->post(route('SubmitCreateProject'), $unsavedProject->toArray());
        $this->get(route('ProjectsIndex'))->assertSee($unsavedProject->name);
    }

    public function testAnUnAuthenticatedUserCanNotCreateAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post(route('SubmitCreateProject'), []);
    }

    public function testAnAuthenticatedUserCanEditAProject()
    {
        $this->be($this->user);
        $project = factory('App\Project')->create();
        $this->patch(route('SubmitEditProject', ['project'=> $project]), [
            'name' => 'edited name',
            'repository' => 'git@github.com:w3c/csswg-test.git',
            'notes' => 'I was edited'
        ]);
        $project = $project->fresh(); # refresh from database
        $this->assertEquals('edited name', $project->name);
        $this->assertEquals('git@github.com:w3c/csswg-test.git', $project->repository);
        $this->assertEquals('I was edited', $project->notes);
    }

    public function testAnUnAuthenticatedUserCanNotEditAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $project = factory('App\Project')->create();
        $this->patch(route('SubmitEditProject', ['project'=> $project]), []);
    }

    public function testAnAuthenticatedUserCanDeleteAProject()
    {
        $this->be($this->user);
        $project = factory('App\Project')->create();
        $servers = factory('App\Server', 3)->create(['project_id' => $project->id]);
        $serverId = $servers->first()->id;
        factory('App\Deployment', 3)->create(['server_id' => $serverId]);
        $response = $this->json('DELETE', $project->path(), ['confirm'=>'true']);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('servers', ['project_id' => $project->id]);
        $this->assertDatabaseMissing('deployments', ['server_id' => $serverId]);
    }

    public function testAnUnAuthenticatedUserCanNotDeleteAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $project = factory('App\Project')->create();
        $response = $this->json('DELETE', $project->path(), ['confirm'=>'true']);
        $response->assertRedirect('/login');
    }
}
