<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServerTest extends TestCase
{
    use DatabaseMigrations;

    protected $server;

    public function setup()
    {
        parent::setUp();
        $this->server = factory('App\Server')->create();
    }

    public function testItHasAnOwner()
    {
        $this->assertInstanceOf('App\User',$this->server->owner);
    }

}
