<?php


namespace Hsnbd\AuditLogger\Tests;


use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

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