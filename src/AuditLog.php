<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Classes\AuditLogManager;
use Hsnbd\AuditLogger\Events\ESMessagePushed;
use Hsnbd\AuditLogger\Interfaces\IAuditLogProcessor;
use Hsnbd\AuditLogger\Classes\AuditLogProcessor;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditLog
 * @package Hsnbd\AuditLogger
 */
class AuditLog
{
    //php artisan queue:work database --queue=listeners
    public function __construct()
    {

    }

    /**
     * @param string|null $message
     * @param IAuditLogProcessor|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    public function debug(?string $message, ?IAuditLogProcessor $auditLogProcessor = null): ?string
    {
        return $this->log($message, __FUNCTION__, $auditLogProcessor);
    }

    /**
     * @param string|null $message
     * @param IAuditLogProcessor|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    public function info(?string $message, ?IAuditLogProcessor $auditLogProcessor = null): ?string
    {
        return $this->log($message, __FUNCTION__, $auditLogProcessor);
    }

    /**
     * @param string|null $message
     * @param string $alertType
     * @param IAuditLogProcessor|null $auditLogProcessor
     * @return string|null
     * @throws \Exception
     */
    private function log(?string $message, string $alertType, ?IAuditLogProcessor $auditLogProcessor = null): ?string
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
     * @param array $data
     */
    private function pushLog(array $data)
    {
        event(new ESMessagePushed($data));
    }
}
