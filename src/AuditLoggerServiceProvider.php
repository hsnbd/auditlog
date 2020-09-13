<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Interfaces\ShouldAuditLog;
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
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'audit-logger');
        $this->app->register(EventServiceProvider::class);
        $this->loadHelpers();
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/config.php' => config_path('audit-logger.php'),
            ], 'config');
        }

        //It seems boot method run twice.

        Event::listen([
                'eloquent.saved: *',
//                'eloquent.created: *',
                'eloquent.updated: *',
                'eloquent.deleted: *',
                'eloquent.restored: *',
            ]
            , function($eventName, $data) {
            $modelClass = str_replace('eloquent.saved: ', '', $eventName ?? '');
//            $modelClass = str_replace('eloquent.created: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.updated: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.deleted: ', '', $modelClass ?? '');
            $modelClass = str_replace('eloquent.restored: ', '', $modelClass ?? '');
            if (
                !empty($modelClass)
                && (new \ReflectionClass($modelClass))->implementsInterface(ShouldAuditLog::class)
            ) {
                \Hsnbd\AuditLogger\Facades\AuditLog::on($data[0] ?? new \stdClass())->setActionType($eventName)->info(null);
            }
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
