<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeploymentTest extends TestCase
{
    use DatabaseMigrations;

    protected $deployment;

    public function setup()
    {
        parent::setUp();
        $this->deployment = factory('App\Deployment')->create();
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->deployment->owner);
    }
}
