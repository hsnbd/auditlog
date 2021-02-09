<?php

namespace Hsnbd\AuditLogger\Classes;

use App\Models\User;
use Hsnbd\AuditLogger\AuditLog;
use Hsnbd\AuditLogger\Interfaces\AuditLogAuthUser;
use Hsnbd\AuditLogger\Interfaces\AuditLogProcessor as AuditLogProcessorInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


/**
 * Class AuditLog
 * @package Hsnbd\AuditLogger
 */
class AuditLogProcessor implements AuditLogProcessorInterface
{
    public ?AuditLogAuthUser $user;
    public ?Model $model;
    public array $data;
    public string $timestamp;
    public ?string $modelActionType;
    public ?string $message;

    public function __construct(array $logData = [])
    {
        if (!empty($logData['model'])) {
            $this->model = $logData['model'];
        } else {
            $this->model = null;
        }

        if (!empty($logData['user'])) {
            $this->user = $logData['user'];
        } elseif (Auth::check()) {
            $this->user = Auth::user();
        } else {
            $this->user = new User();
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
    }

    public function getUserInfo(): array
    {
        /** @var User $user */
        if (is_null($this->user) && Auth::check()) {
            $this->user = \Auth::user();
        } elseif (is_null($this->user) && !Auth::check()) {
            return [];
        }

        return [
            "id" => $this->user->id ?? null,
            'username' => $this->user->username ?? null,
            'mobile' => $this->user->cell_phone ?? null
        ];
    }

    public function processAuditLog(string $alertType): array
    {
        $auditLog = new AuditLog();
        $this->modelActionType = $auditLog->parseModelEventName($this->modelActionType);

        return $auditLog->processLog($alertType, $this);
    }

}
