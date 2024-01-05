<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $ownerUser;
    public $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
