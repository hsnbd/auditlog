<?php
namespace Hsnbd\AuditLogger\Facades;

use Hsnbd\AuditLogger\Interfaces\AuditLogProcessor;
use Illuminate\Support\Facades\Facade;

/**
 * Class Logger
 * @package AuditLog\Facades
 * @method static string info(?string $message, ?AuditLogProcessor $auditLogProcessor = null)
 * @method static string debug(?string $message, ?AuditLogProcessor $auditLogProcessor = null)
 */
class AuditLog extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'audit-logger';
    }
}
