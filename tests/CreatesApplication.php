<?php

namespace Tests\Traits;

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Facade;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Facade::clearResolvedInstances();

        $app->boot();

        $this->buildBaseTestEnvironment();

        return $app;
    }

    private function buildBaseTestEnvironment()
    {
        if (filesize(database_path('stub.sqlite')) === 0) {
            Artisan::call('migrate');
            Artisan::call('db:seed');
        }

        putenv('DB_DATABASE_LITE=database/testing.sqlite');
    }
}
