<?php

namespace AuditLogger;

use App\Models\User;
use AuditLogger\Events\ESMessagePushed;

class AuditLog
{
    //php artisan queue:work database --queue=listeners
    protected string $formatter = "";
    public ?string $message = "";
    public array $data = [];

    public function info(?string $message)
    {
        $this->message = $message;
        $this->pushMessage();
        return $message;
    }

    public function debug(?string $message)
    {
        $this->message = $message;

        $this->pushMessage();
        return $message;
    }

    private function pushMessage()
    {
        $this->processMessage();

        event(new ESMessagePushed($this));
    }

    protected function processMessage()
    {
        /** @var User $authUser */
        $authUser = \Auth::user();
        $this->data = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'action_type' => 'create',
            'alert_type' => 'info',
            'log_type' => 'application_log',
            'browser' => request()->header('User-Agent'),
            'ip_addr' => '180.148.214.181',
//            'ip_addr' => request()->ip(),
            'message' => !empty($this->message) ? ($authUser->username . '::' . $authUser->cell_phone . ': ' . $this->message) : '',
            "user" => [
                "id" => $authUser->id,
                'username' => $authUser->username,
                'mobile' => $authUser->cell_phone,
                'office' => optional($authUser->officeInformation)->title,
                'office_designation' => optional($authUser->officeDesignation)->title,
            ]
        ];
    }
}
