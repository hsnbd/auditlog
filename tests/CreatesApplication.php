<?php

namespace Hsnbd\AuditLogger\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require __DIR__.'/../laravel.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
}
