<?php

namespace Hsnbd\AuditLogger;

use App\Models\User;
use Hsnbd\AuditLogger\Events\ESMessagePushed;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuditLog
 * @package Hsnbd\AuditLogger
 */
class AuditLog
{
    //php artisan queue:work database --queue=listeners
    private ?object $authModel = null;
    private ?object $performerModel = null;
    private ?string $performerModelId;
    private ?string $timestamp;
    private ?string $modelActionType;
    protected string $formatter = "";
    public ?string $message = "";
    public string $authUsernameNMobile = '';
    public array $data = [];

    public function __construct()
    {

    }

    public function setMessage(?string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function info(?string $message, array $data = []): ?string
    {
        $this->setMessage($message);
        $this->pushMessage(__FUNCTION__, $data);
        return $this->message;
    }

    public function debug(?string $message, array $data = []): ?string
    {
        $this->setMessage($message);
        $this->pushMessage(__FUNCTION__, $data);
        return $this->message;
    }

    public function by(?object $model): self
    {
        if (!is_null($model) ) {
            $this->authModel = $model;
        }
        return $this;
    }

    public function on(?object $model): self
    {
        if (!is_null($model)) {
            $this->performerModel = $model;
        }
        return $this;
    }

    public function at(?string $timestamp): self
    {
        if (!is_null($timestamp)) {
            $this->timestamp = $timestamp;
        }
        return $this;
    }

    public function setActionType(?string $actionType): self
    {
        if (!is_null($actionType)) {
            $this->modelActionType = $actionType;
        }
        return $this;
    }

    private function pushMessage(string $alertType, array $data = []): void
    {
        $this->processLog();
        $this->processMessage($data, 'application_log', $alertType);

        event(new ESMessagePushed($this));
    }

    protected function processLog()
    {
        if (empty($this->modelActionType)) {
            $this->modelActionType = $this->performerModel ? ($this->performerModel->wasRecentlyCreated ? 'saved' : 'updated') : 'affected';
        } else {
            preg_match('/eloquent\.([\w]+):/', ($this->modelActionType ?? ''), $matches);
            $modelEvent = !empty($matches[1]) ? $matches[1] : 'affected';
            if ($modelEvent === 'saved' && $this->performerModel) {
                if (!$this->performerModel->wasRecentlyCreated) {
                    $modelEvent = 'updated';
                } else {
                    $modelEvent = 'created';
                }
            }
            $this->modelActionType = $modelEvent;
        }

        if (empty($this->message)) {
            $this->setMessage((class_basename($this->performerModel) ?? 'A Model') . ' has been '. $this->modelActionType .' at '. ($this->timestamp ?? date('Y-m-d H:i:s')));
        }

        $this->performerModelId = $this->performerModel ? ($this->performerModel->getAttribute('id') ?? $this->performerModel->id) : null;

        $this->authModel = $this->authModel ?: (Auth::user() ?? new \stdClass());
    }

    protected function processMessage(array $data, string $logType, string $alertType): void
    {
        /** @var User $authUser */
        $authUser = new \stdClass();
        if(Auth::check()) {
            $authUser = \Auth::user();
        }

        $this->authUsernameNMobile = (($authUser->username ?? 'undefined') . '::' . ($authUser->cell_phone ?? 'undefined') . ': ');
        $this->data = array_merge(
            $this->data,
            [
                'action_model_class' => $this->performerModel ?  get_class($this->performerModel) : null,
                'action_model_id' => $this->performerModelId ?? null,
                'timestamp' => ($this->timestamp ?? date('Y-m-d H:i:s')),
                'action_type' => $this->modelActionType,
                'alert_type' => $alertType,
                'log_type' => $logType,
                'browser' => request()->header('User-Agent'),
                'ip_addr' => '180.148.214.181',
//            'ip_addr' => request()->ip(),
                'message' => $this->authUsernameNMobile . (!empty($this->message) ? $this->message : 'take an action'),
                "user" => [
                    "id" => $authUser->id ?? null,
                    'username' => $authUser->username ?? null,
                    'mobile' => $authUser->cell_phone ?? null,
                    'office' => optional($authUser->officeInformation)->title ?? null,
                    'office_designation' => optional($authUser->officeDesignation)->title ?? null,
                ]
            ],
            $data
        );
    }
}
