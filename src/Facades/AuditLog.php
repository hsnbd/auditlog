<?php
namespace Hsnbd\AuditLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Logger
 * @package AuditLog\Facades
 * @method static string info(?string $message, array $data = [])
 * @method static string debug(?string $message, array $data = [])
 * @method static self by(?object $model)
 * @method static self on(?object $model)
 * @method static self at(?string $timestamp)
 * @method static self setActionType(?string $actionType)
 */
class AuditLog extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'audit-logger';
    }
}
