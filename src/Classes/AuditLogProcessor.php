<?php

namespace Hsnbd\AuditLogger\Classes;

use Hsnbd\AuditLogger\AuditLog;
use Hsnbd\AuditLogger\Interfaces\AuditLogAuthUser;
use Hsnbd\AuditLogger\Interfaces\IAuditLogProcessor;
use Illuminate\Database\Eloquent\Model;


/**
 * Class AuditLogProcessor
 * @package Hsnbd\AuditLogger
 */
class AuditLogProcessor implements IAuditLogProcessor
{
    public ?AuditLogAuthUser $user;
    public ?Model $model;
    public array $data;
    public string $timestamp;
    public ?string $modelActionType = null;
    public ?string $message = null;
    public ?string $alertType = null;

    /**
     * AuditLogProcessor constructor.
     * @param array $logData
     */
    public function __construct(array $logData = [])
    {
        if (!empty($logData['model'])) {
            $this->model = $logData['model'];
        } else {
            $this->model = null;
        }

        if (!empty($logData['user'])) {
            $this->user = $logData['user'];
        } else {
            $user = config('audit-logger.userModel');
            $this->user = new $user;
        }


        if (!empty($logData['data'])) {
            $this->data = $logData['data'];
        } else {
            $this->data = [];
        }

        if (!empty($logData['timestamp'])) {
            $this->timestamp = $logData['timestamp'];
        } else {
            $this->timestamp = date('Y-m-d H:i:s');
        }

        if (!empty($logData['modelActionType'])) {
            $this->modelActionType = $logData['modelActionType'];
        }

        if (!empty($logData['alertType'])) {
            $this->alertType = $logData['alertType'];
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAuditLogData(): array
    {
        $auditLog = new AuditLog();
        $auditLog->setAuditLogProcessorData($this);

        return $auditLog->getParsedAuditLogData();
    }

    /**
     * @return array
     */
    public function getUserMetaData(): array
    {
        if (is_null($this->user)) {
            return [];
        }

        return $this->user->getAuditLogUserMetaData();
    }

    public function getModel(): ?Model
    {
        return !empty($this->model) ? $this->model : null;
    }

    public function getTimestamp(): string
    {
        return !empty($this->timestamp) ? $this->timestamp : date('Y-m-d');
    }

    public function getModelActionType(): ?string
    {
        return !empty($this->modelActionType) ? $this->modelActionType : null;
    }

    public function getMessage(): ?string
    {
        return !empty($this->message) ? $this->message : null;
    }

    public function getAlertType(): string
    {
        return !empty($this->alertType) ? $this->alertType : 'log';
    }

    public function getLogType(): string
    {
        return !empty($this->model) ? 'eloquent_log' : 'application_log';
    }

    public function getBrowserAgent(): ?string
    {
        return request()->header('User-Agent');
    }

    public function getIpAddress(): ?string
    {
        return request()->ip();
    }

    public function getAuditLogFillableFields(): array
    {
        return [
            'timestamp',
        ];
    }
}
