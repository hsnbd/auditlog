<?php

namespace Hsnbd\AuditLogger\Classes;

use Hsnbd\AuditLogger\AuditLog;
use Hsnbd\AuditLogger\Interfaces\IAuditLogProcessor;
use Hsnbd\AuditLogger\Interfaces\ShouldAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuditLogProcessor
 * @package Hsnbd\AuditLogger
 */
class AuditLogManager
{
    public const TYPE_ELOQUENT = 'eloquent';
    public const TYPE_MANUAL = 'manual';

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
                $auditLogProcessorClass = config('audit-logger.log_processor');

                if (!$auditLogProcessorClass) {
                    throw new \Exception('Please specify log processor');
                }

                if (!(new \ReflectionClass($auditLogProcessorClass))->implementsInterface(\Hsnbd\AuditLogger\Interfaces\AuditLogProcessor::class)) {
                    throw new \Exception('log processor should implement ' . \Hsnbd\AuditLogger\Interfaces\AuditLogProcessor::class . 'interface');
                }

                $auditLogProcessor = new $auditLogProcessorClass();
                $auditLogProcessor->model = $data[0];
                $auditLogProcessor->modelActionType = $eventName;

                \Hsnbd\AuditLogger\Facades\AuditLog::debug(null, $auditLogProcessor);
            }
        });
    }

    public static function validateModelLogData(array $data, array $rules): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, $rules);
    }

    public static function processModelData(?Model $model, ?string $actionType): array
    {
        $data = [];

        if (is_null($model)) {
            return $data;
        }

        if (empty($actionType)) {
            $actionType = $model->wasRecentlyCreated ? 'saved' : 'updated';
        }

        $message = (class_basename($model) ?? 'A Model') . ' has been ' . $actionType;

        $modelId = $model->getAttribute('id');

        $data = [
            'action_model_class' => get_class($model),
            'action_model_id' => $modelId,
            'action_model_changes' => self::getModelChangesData($model),
            'action_type' => $actionType,
            'message' => $message,
        ];

        return $data;
    }


    /**
     * @param IAuditLogProcessor $auditLogProcessor
     * @return array
     */
    public static function getLogMetaData(IAuditLogProcessor $auditLogProcessor): array
    {
        return [
            'timestamp' => $auditLogProcessor->getTimestamp(),
            'alert_type' => $auditLogProcessor->getAlertType(),
            'log_type' => $auditLogProcessor->getLogType(),
            'browser' => $auditLogProcessor->getBrowserAgent(),
            'ip_addr' => $auditLogProcessor->getIpAddress(),
        ];
    }
    /**
     * @param IAuditLogProcessor $auditLogProcessor
     * @return array
     */
    public static function getBasicLogData(IAuditLogProcessor $auditLogProcessor): array
    {
        if (is_null($auditLogProcessor->getModel())) {
            return [];
        }
        return AuditLogManager::processModelData($auditLogProcessor->getModel(), $auditLogProcessor->getModelActionType());
    }

    /**
     * @param Model $model
     * @return false|string
     */
    public static function getModelChangesData(Model $model)
    {
        $changes = $model->getChanges();
        $allowedChanges = [];

        if (
            $changes
            && property_exists($model, 'auditLogIgnore')
            && is_array($model->auditLogIgnore)
            && count($model->auditLogIgnore)
        ) {
            foreach ($changes as $key => $value) {
                if (!in_array($key, $model->auditLogIgnore)) {
                    $allowedChanges[$key] = $value;
                }
            }
        } else {
            $allowedChanges = $changes;
        }

        return json_encode($allowedChanges);
    }

    public function getParsedAuditLogData(AuditLogProcessor $auditLogProcessor): array
    {
        $basicLogData = AuditLogManager::getBasicLogData($auditLogProcessor);
        $logMetaData = AuditLogManager::getLogMetaData($auditLogProcessor);
        $userLogData = $auditLogProcessor->getUserMetaData();

        $log = array_merge($basicLogData, $logMetaData, ['user' => $userLogData]);

        if (!empty($auditLogProcessor->message)) {
            $log['message'] = $auditLogProcessor->message;
        }

        return $log;
    }
}
