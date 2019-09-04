<?php

namespace Tests\phpunit\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Tests\phpunit\CreatesApplication;
}
