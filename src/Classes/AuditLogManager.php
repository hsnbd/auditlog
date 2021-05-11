<?php

namespace Hsnbd\AuditLogger\Classes;

use Hsnbd\AuditLogger\Interfaces\ShouldAuditLog;
use Illuminate\Database\Eloquent\Model;
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
                /** @var Model $instance */
                $instance = $data[0];

                $actionType = self::parseModelEventName($eventName);
                if (empty($actionType)) {
                    $actionType = $instance->wasRecentlyCreated ? 'saved' : 'updated';
                }
                $message = $modelClass . ' has been ' . $actionType;

                $logMeta = [
                    'log_url' => request()->url(),
                    'action_model' => $modelClass,
                    'action_table' => $instance->getTable(),
                    'action_id' => $instance->getAttribute('id'),
                    'operation_type' => $actionType,
                ];
                \Hsnbd\AuditLogger\Facades\AuditLog::debug($message, $instance->toArray(), $logMeta, [], []);
            }
        });
    }

    /**
     * @param string|null $actionType
     * @return string
     */
    public static function parseModelEventName(?string $actionType): string
    {
        $modelEvent = 'affected';

        if (!is_null($actionType)) {
            preg_match('/eloquent\.([\w]+):/', ($actionType ?? ''), $matches);
            $modelEvent = !empty($matches[1]) ? $matches[1] : $modelEvent;
        }

        return $modelEvent;
    }
}
