<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;

    private static bool $dbWiped = false;
    
    public $ownerUser;
    public $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        if (static::$dbWiped) {
            return;
        }

        if ('testing' === app()->environment()) {
            Artisan::call('db:wipe');
        }

        static::$dbWiped = true;
    }
}
