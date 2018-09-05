<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;

    public function setup()
    {
        parent::setUp();
        $this->project = factory('App\Project')->create();
    }

    public function testAUserCanSeeAllProjects()
    {
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }

    public function testAUserCanViewASingleProject()
    {
        $response = $this->get('/projects/'.$this->project->id);
        $response->assertStatus(200);
        $response->assertSee($this->project->Name);
    }





}
