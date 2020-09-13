<?php
namespace AuditLogger;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends Serviceprovider {
    protected $listen = [
        \AuditLogger\Events\ESMessagePushed::class => [
            \AuditLogger\Listeners\ESMessagePush::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }
}
