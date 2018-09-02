<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    public function testAUserCanSeeAllProjects()
    {
        $project = factory('App\Project')->create();
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee($project->Name);
    }

    public function testAUserCanViewASingleProject()
    {
        $project = factory('App\Project')->create();
        $response = $this->get('/projects/'.$project->id);
        $response->assertStatus(200);
        $response->assertSee($project->Name);
    }





}
