<?php

namespace Hsnbd\AuditLogger\Tests;
use Hsnbd\AuditLogger\AuditLoggerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PHPUnit\Framework\Constraint\StringContains;

class TestCase extends OrchestraTestCase
{


    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuditLoggerServiceProvider::class,
        ];
    }
}
