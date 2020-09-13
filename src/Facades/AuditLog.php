<?php
namespace AuditLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Logger
 * @package AuditLog\Facades
 * @method static info()
 * @method static debug(string $message)
 */
class AuditLog extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'audit-logger';
    }
}
