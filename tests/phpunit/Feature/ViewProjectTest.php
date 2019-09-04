<?php

namespace Tests\Feature;

use Tests\phpunit\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewProjectTest extends TestCase
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

    public function testAnAuthenticatedUserCanSeeAllProjects()
    {
        $this->be($this->user);
        $response = $this->get(route('Projects'));
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }


    public function testAnUnauthenticatedUserCanNotSeeAllProjects()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('Projects'));
    }

    public function testAnAuthenticatedUserCanViewASingleProject()
    {
        $this->be($this->user);
        $response = $this->get(route('ShowProject',['project'=> $this->project]));
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewASingleProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('ShowProject',['project'=> $this->project]));
    }


    public function testAnAuthenticatedUserCanViewProjectCreatePage()
    {
        $this->be($this->user);
        $response = $this->get(route('CreateProject'));
        $response->assertStatus(200);
        $response->assertSee('Add Project');
    }

    public function testAnUnauthenticatedUserCanNotViewProjectCreatePage()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get(route('CreateProject'));
    }

}
