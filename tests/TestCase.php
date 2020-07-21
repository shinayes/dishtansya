<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\Traits\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() : void
    {
        parent::setUp();

        copy(database_path('stub.sqlite'), database_path('testing.sqlite'));

        Config::set('database.connections.sqlite.database', 'database/testing.sqlite');
    }

    protected function tearDown() : void
    {
        DB::connection()->setPdo(null);

        parent::tearDown();
    }
}
