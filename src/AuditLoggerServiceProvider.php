<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Classes\AuditLogManager;
use Hsnbd\AuditLogger\Commands\AuditlogTesting;
use Hsnbd\AuditLogger\Commands\PingAuditLog;
use Illuminate\Support\ServiceProvider;


class AuditLoggerServiceProvider extends ServiceProvider
{
    public string $packageAlias = 'audit-logger';

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->bind($this->packageAlias, function ($app) {
            return new AuditLog();
        });

        $this->mergeConfigFrom($this->getConfigFilePath(), $this->packageAlias);
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
            $this->commands([
                PingAuditLog::class,
                AuditlogTesting::class
            ]);

            $this->publishes([
                $this->getConfigFilePath() => config_path($this->packageAlias . '.php'),
            ], 'config');
        }

        /**
         * For model log using ShouldAuditLog
         */
        AuditLogManager::registerEloquentModelEventListener();
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        require_once __DIR__ . '/helpers/helper.php';
    }

    protected function getConfigFilePath(): string
    {
        return __DIR__ . '/config/config.php';
    }
}
