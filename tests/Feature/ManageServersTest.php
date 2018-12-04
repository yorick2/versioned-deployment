<?php

namespace Tests\Feature;

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class manageServersTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;
    protected $server;
    protected $user;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
        $this->server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->user = factory('App\User')->create();
    }

    /**
     * check the slug for a server is unique for its project
     * - check for multiple projects
     * - check for numbers added to the slug of over 1 digit (10+)
     * - check the slug is not based on last server added
     */
    public function testAServerRequiresAUniqueSlugForThisProject()
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
        $this->post(route('SubmitCreateServer',['project'=> $project1]) , $dataArray1);
        $this->assertTrue(Server::whereSlug('foo-bar-2')->exists());
        $this->post(route('SubmitCreateServer',['project'=> $project1])  , $dataArray1);
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
        $this->post(route('SubmitCreateServer',['project'=> $project2]), $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-2'],
            ['project_id', '=', $project2->id],
        ])->exists());
        $this->post(route('SubmitCreateServer',['project'=> $project2]), $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-3'],
            ['project_id', '=', $project2->id],
        ])->exists());
        factory('App\Server')->create([
            'name' => 'foo bar',
            'slug' => 'foo-bar-10',
            'project_id' => $project2->id
        ]);
        $this->post(route('SubmitCreateServer',['project'=> $project2]), $dataArray2);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-11'],
            ['project_id', '=', $project2->id],
        ])->exists());


        $this->post(route('SubmitCreateServer',['project'=> $project1]) , $dataArray1);
        $this->assertTrue(Server::where([
            ['slug', '=', 'foo-bar-4'],
            ['project_id', '=', $project1->id],
        ])->exists());
    }

    public function testAnAuthenticatedUserCanCreateAServerForAProject()
    {
        $this->be($this->user);
        $unsavedServer = factory('App\Server')->make();
        $this->post(route('SubmitCreateServer',['project'=> $unsavedServer->project]), $unsavedServer->toArray());
        $this->get(route('ServersIndex',['project'=> $unsavedServer->project]) )->assertSee($unsavedServer->name);
    }

    public function testAnUnAuthenticatedUserCanNotCreateAServerForAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post(route('SubmitCreateServer',['project'=> $this->project]), []);
    }

    public function testAnAuthenticatedUserCanEditAServerForAProject()
    {
        $this->be($this->user);
        $server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $this->patch(route('SubmitEditServer',['server'=>$server, 'project'=> $this->project]) ,[
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
        $this->patch(route('SubmitEditServer',['server'=>$this->server, 'project'=> $this->project]),[]);
    }

    public function testAnAuthenticatedUserCanDeleteAServerForAProject()
    {
        $this->be($this->user);
        $server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        factory('App\Deployment', 3)->create(['server_id' => $server->id]);
        $response = $this->json('DELETE',route('DestroyServer',['server'=>$server, 'project'=> $this->project]));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('servers',['id' => $server->id]);
        $this->assertDatabaseMissing('deployments',['server_id' => $server->id]);
    }

    public function testAnUnAuthenticatedUserCanNotDeleteAServerForAProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $server = factory('App\Server')->create(['project_id'=>$this->project->id]);
        $response = $this->json('DELETE', route('DestroyServer',['server'=>$server, 'project'=> $this->project]));
        $response->assertRedirect('/login');
    }
}
