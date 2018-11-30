<?php

namespace Tests\Feature;

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class manageServersTest extends TestCase
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

    /**
     * check the slug is unique for its project
     * - check for multiple projects
     * - check for numbers added to the slug of over 1 digit (10+)
     * - check the slug is not based on last server added
     */
    public function testItRequiresAUniqueSlugForAProject()
    {
        $this->be($this->user);
        $project1 = factory('App\Project')->create();
        $server = factory('App\Server')->create([
            'name' => 'foo bar',
            'slug' => 'foo-bar',
            'project_id' => $project1->id
        ]);
        $dataArray1 = $server->toArray();
        $this->assertEquals($server->fresh()->slug, 'foo-bar');
        $this->post($project1->path().'/create-server' , $dataArray1);
        $this->assertTrue(Server::whereSlug('foo-bar-2')->exists());
        $this->post($project1->path().'/create-server' , $dataArray1);
        $this->assertTrue(Server::whereSlug('foo-bar-3')->exists());


        $project2 = factory('App\Project')->create();
        $server = factory('App\Server')->create([
            'name' => 'foo bar',
            'slug' => 'foo-bar',
            'project_id' => $project2->id
        ]);
        $dataArray2 = $server->toArray();
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar'],
            ['project_id', '=', $project2->id],
        ])->exists());
        $this->post($project2->path().'/create-server' , $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-2'],
            ['project_id', '=', $project2->id],
        ])->exists());
        $this->post($project2->path().'/create-server' , $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-3'],
            ['project_id', '=', $project2->id],
        ])->exists());
        factory('App\Server')->create([
            'name' => 'foo bar',
            'slug' => 'foo-bar-10',
            'project_id' => $project2->id
        ]);
        $this->post($project2->path().'/create-server' , $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-11'],
            ['project_id', '=', $project2->id],
        ])->exists());


        $this->post($project1->path().'/create-server' , $dataArray1);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-4'],
            ['project_id', '=', $project1->id],
        ])->exists());
    }

    public function testAnAuthenticatedUserCanCreateAServerForAProject()
    {
        $this->be($this->user);
        $unsavedServer = factory('App\Server')->make();
        $this->post($unsavedServer->project->path().'/create-server', $unsavedServer->toArray());
        $this->get($unsavedServer->project->path().'/servers')->assertSee($unsavedServer->name);
    }

    public function testAnUnAuthenticatedUserCanNotCreateAServerForAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post($this->project->path().'/create-server', []);
    }

    public function testAnAuthenticatedUserCanEditAServerForAProject()
    {
        $this->be($this->user);
        $server = factory('App\Server')->create();
        $this->patch($server->path(),[
            'name' => 'edited name',
            'deploy_host' => 'http://edited.com',
            'deploy_port' => '123',
            'deploy_location' => '/edited',
            'deploy_user' => 'edited_user',
            'deploy_password' => 'edited-password',
            'notes' => 'I was edited'
        ]);
        $server = $server->fresh(); # refresh from database
        $this->assertEquals('edited name',$server->name);
        $this->assertEquals('http://edited.com',$server->deploy_host);
        $this->assertEquals('123',$server->deploy_port);
        $this->assertEquals('/edited',$server->deploy_location);
        $this->assertEquals('edited_user',$server->deploy_user);
        $this->assertEquals('edited-password',$server->deploy_password);
        $this->assertEquals('I was edited',$server->notes);
    }

    public function testAnUnAuthenticatedUserCanNotEditAServerForAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $server = factory('App\Server')->create();
        $this->patch($server->path(),[]);
    }

    public function testAnAuthenticatedUserCanDeleteAServerForAProject()
    {
        $this->be($this->user);
        $server = factory('App\Server')->create();
        factory('App\Deployment', 3)->create(['server_id' => $server->id]);
        $response = $this->json('DELETE', $server->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('servers',['id' => $server->id]);
        $this->assertDatabaseMissing('deployments',['server_id' => $server->id]);
    }

    public function testAnUnAuthenticatedUserCanNotDeleteAServerForAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $server = factory('App\Server')->create();
        $response = $this->json('DELETE', $server->path());
        $response->assertRedirect('/login');
    }
}
