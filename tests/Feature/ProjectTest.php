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
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }


    public function testAnUnauthenticatedUserCanNotSeeAllProjects()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get('/projects');
    }

    public function testAnAuthenticatedUserCanViewASingleProject()
    {
        $this->be($this->user);
        $response = $this->get($this->project->path());
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }

    public function testAnUnauthenticatedUserCanNotViewASingleProject()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get($this->project->path());
    }





}
