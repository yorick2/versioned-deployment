<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends TestCase
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

}
