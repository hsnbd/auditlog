<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Classes\AuditLogManager;
use Hsnbd\AuditLogger\Events\ESMessagePushed;
use Hsnbd\AuditLogger\Interfaces\AuditLogProcessor as AuditLogProcessorInterface;
use Hsnbd\AuditLogger\Classes\AuditLogProcessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class AuditLog
 * @package Hsnbd\AuditLogger
 */
class AuditLog
{
    //php artisan queue:work database --queue=listeners
    public array $metaDataRules = [
        'timestamp' => 'required|timestamp',
        'alert_type' => 'required',
        'log_type' => 'required',
        'browser' => 'required',
        'ip_addr' => 'required',
    ];

    public array $userDataRules = [
        "id" => 'required',
        'username' => 'required',
        'mobile' => 'required',
        'office' => 'required',
        'office_designation' => 'required',
    ];


    public function __construct()
    {

    }

    /**
     * @param string|null $message
     * @param AuditLogProcessorInterface|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    public function debug(?string $message, ?AuditLogProcessorInterface $auditLogProcessor = null): ?string
    {
        return $this->log($message, __FUNCTION__, $auditLogProcessor);
    }

    /**
     * @param string|null $message
     * @param AuditLogProcessorInterface|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    public function info(?string $message, ?AuditLogProcessorInterface $auditLogProcessor = null): ?string
    {
        return $this->log($message, __FUNCTION__, $auditLogProcessor);
    }

    /**
     * @param string|null $message
     * @param string $alertType
     * @param AuditLogProcessorInterface|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    private function log(?string $message, string $alertType, ?AuditLogProcessorInterface $auditLogProcessor = null): ?string
    {
        if (is_null($auditLogProcessor)) {
            $auditLogProcessor = new AuditLogProcessor();
        }
        if (is_null($auditLogProcessor->alertType)) {
            $auditLogProcessor->alertType = $alertType;
        }
        if (is_null($auditLogProcessor->message)) {
            $auditLogProcessor->message = $message;
        }

        $log = $auditLogProcessor->processAuditLog();

        $this->pushLog($log);

        return !empty($log['message']) ? $log['message'] : '';
    }

    /**
     * @param string|null $actionType
     * @return string
     */
    public function parseModelEventName(?string $actionType): string
    {
        $modelEvent = 'affected';

        if (!is_null($actionType)) {
            preg_match('/eloquent\.([\w]+):/', ($actionType ?? ''), $matches);
            $modelEvent = !empty($matches[1]) ? $matches[1] : $modelEvent;
        }

        return $modelEvent;
    }

    /**
     * @param AuditLogProcessorInterface|null $auditLogProcessor
     * @return array
     * @throws \Exception
     */
    public function processLog(?AuditLogProcessorInterface $auditLogProcessor): array
    {
        $basicLogData = $this->getBasicLogData($auditLogProcessor->model, $auditLogProcessor->modelActionType);
        $logMetaData = $this->getLogMetaData($auditLogProcessor->model, $auditLogProcessor->alertType, $auditLogProcessor->timestamp);
        $userLogData = $auditLogProcessor->getUserInfo();

        $log = array_merge($basicLogData, $logMetaData, ['user' => $userLogData]);

        if (!empty($auditLogProcessor->message)) {
            $log['message'] = $auditLogProcessor->message;
        }

        return $log;
    }

    /**
     * @param array $data
     */
    private function pushLog(array $data)
    {
        event(new ESMessagePushed($data));
    }

    /**
     * @param Model|null $model
     * @param string|null $modelActionType
     * @return array
     * @throws \Exception
     */
    protected function getBasicLogData(?Model $model, ?string $modelActionType): array
    {
//        $modelDataRules = [
//            'action_model_class' => 'required',
//            'action_model_id' => 'required',
//            'action_model_changes' => 'required',
//            'action_type' => 'required',
//        ];

        if (is_null($model)) {
            return [];
        }

//        $modelValidate = AuditLogManager::validateModelLogData($modelData, $modelDataRules);

//        if ($modelValidate->fails()) {
//            Log::debug($modelValidate->errors());
//            throw new \Exception('Required data not found. Log: ' . $modelValidate->errors()->first());
//        }

        return AuditLogManager::processModelData($model, $modelActionType);
    }

    /**
     * @param Model|null $model
     * @param string $alertType
     * @param string|null $timestamp
     * @return array
     */
    protected function getLogMetaData(?Model $model, string $alertType, ?string $timestamp): array
    {
        return [
            'timestamp' => $timestamp ? $timestamp : date('Y-m-d H:i:s'),
            'alert_type' => $alertType,
            'log_type' => !is_null($model) ? 'eloquent_log' : 'application_log',
            'browser' => request()->header('User-Agent'),
            'ip_addr' => request()->ip(),
        ];
    }
}
