<?php

namespace AuditLogger;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AuditLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->bind('audit-logger', function($app) {
            return new AuditLog();
        });

        $this->app->register(EventServiceProvider::class);
        $this->loadHelpers();
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        //It seems boot method run twice.

        Event::listen(['eloquent.saved: *', 'eloquent.created: *'], function($eventName, $data) {

        });
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        require_once __DIR__ . '/helpers/helper.php';
    }
}
