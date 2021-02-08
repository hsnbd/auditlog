<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Interfaces\ShouldAuditLog;
use Illuminate\Support\Facades\Event;

/**
 * Class AuditLogProcessor
 * @package Hsnbd\AuditLogger
 */
class AuditLogManager
{
    /**
     * Eloquent event is to log Eloquent Model directly by listening events.
     * @return array
     */
    public static function getEloquentEvents(): array
    {
        return config('audit-logger.eloquent_event_for_log') ?? [];
    }

    /**
     * For model log using ShouldAuditLog
     */
    public static function registerEloquentModelEventListener()
    {
        Event::listen(self::getEloquentEvents(), function ($eventName, $data) {
            $modelClass = str_replace('eloquent.saved: ', '', $eventName ?? '');
            $modelClass = str_replace('eloquent.created: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.updated: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.deleted: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.restored: ', '', $modelClass ?? '');

            if (
                !empty($modelClass)
                && (new \ReflectionClass($modelClass))->implementsInterface(ShouldAuditLog::class)
            ) {
                \Hsnbd\AuditLogger\Facades\AuditLog::on($data[0] ?? new \stdClass())->setActionType($eventName)->info(null);
            }
        });
    }
}
