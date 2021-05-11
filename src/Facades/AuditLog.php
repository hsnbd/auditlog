<?php
namespace Hsnbd\AuditLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Logger
 * @package AuditLog\Facades
 * @method static string info(string $message, ?array $data, ?array $logMeta, ?array $userMeta, ?array $applicationMeta)
 * @method static string debug(string $message, ?array $data, ?array $logMeta, ?array $userMeta, ?array $applicationMeta)
 */
class AuditLog extends Facade {
    protected static function getFacadeAccessor(): string
    {
        return 'audit-logger';
    }
}
