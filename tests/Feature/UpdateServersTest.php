<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class updateServersTest extends TestCase
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

}
