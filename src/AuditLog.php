<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Events\ESMessagePushed;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuditLog
 * @package Hsnbd\AuditLogger
 */
class AuditLog
{
    //php artisan queue:work database --queue=listeners

    /**
     * @param string|null $message
     * @param array|null $data
     * @param array $logMeta
     * @param array $userMeta
     * @param array $applicationMeta
     * @return string|null
     */
    public function debug(?string $message = '', ?array $data = [], ?array $logMeta = [], ?array $userMeta = [], ?array $applicationMeta = []): ?string
    {
        return $this->log(__FUNCTION__, $message, $data, $logMeta, $userMeta, $applicationMeta);
    }

    /**
     * @param string|null $message
     * @param array|null $data
     * @param array $logMeta
     * @param array $userMeta
     * @param array $applicationMeta
     * @return string|null
     */
    public function info(?string $message = '', ?array $data = [], ?array $logMeta = [], ?array $userMeta = [], ?array $applicationMeta = []): ?string
    {
        return $this->log(__FUNCTION__, $message, $data, $logMeta, $userMeta, $applicationMeta);
    }

    /**
     * @param string|null $alertType
     * @param string|null $message
     * @param array|null $data
     * @param array|null $logMeta
     * @param array|null $userMeta
     * @param array|null $applicationMeta
     * @return string|null
     */
    private function log(string $alertType, ?string $message, ?array $data, ?array $logMeta, ?array $userMeta, ?array $applicationMeta): ?string
    {
        if (empty($message)) {
            $message = '';
        }
        if (empty($data)) {
            $data = [];
        }
        if (empty($logMeta)) {
            $logMeta = [];
        }
        if (empty($userMeta)) {
            $userMeta = [];
        }
        if (empty($applicationMeta)) {
            $applicationMeta = [];
        }

        $log = [
            'application_meta' => $this->getApplicationMeta($applicationMeta),
            'log_meta' => $this->getLogMeta($logMeta, $alertType),
            'log_data' => $data,
            'user_meta' => $this->getUserMeta($userMeta),
            'client_ip' => request()->ip(),
            'browser' => request()->userAgent(),
            'message' => $message,
        ];

        $this->pushLog($log);

        return !empty($log['message']) ? $log['message'] : '';
    }

    /**
     * @param array $data
     */
    private function pushLog(array $data)
    {
        event(new ESMessagePushed($data));
    }

    private function getApplicationMeta(array $applicationMeta): array
    {
        $meta = $applicationMeta;
        if (empty($meta['application_name'])) {
            $meta['application_name'] = env('APP_NAME');
        }

        if (empty($meta['server_ip'])) {
            $meta['server_ip'] = request()->server('SERVER_ADDR');
        }

        return $meta;
    }

    private function getLogMeta(array $logMeta, string $alertType): array
    {
        $meta = $logMeta;
        if (empty($meta['log_type'])) {
            $meta['log_type'] = $alertType;
        }

        if (empty($meta['log_url'])) {
            $meta['log_url'] = request()->url();
        }

        return $meta;
    }


    private function getUserMeta(array $userMeta)
    {
        $meta = [];
        if (count($userMeta)) {
            $meta = $userMeta;
        } elseif (Auth::check()) {
            $meta = Auth::user();
        }

        return $meta;
    }
}
