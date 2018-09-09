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


}
