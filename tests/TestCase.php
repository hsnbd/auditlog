<?php

namespace Hsnbd\AuditLogger\Tests;


abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            "Hsnbd\\AuditLogger\\AuditLoggerServiceProvider"
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            "AuditLog" => "Hsnbd\\AuditLogger\\Facades\\AuditLog"
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'Asia/Dhaka';
    }
}