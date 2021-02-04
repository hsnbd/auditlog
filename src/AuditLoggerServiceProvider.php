<?php

namespace Hsnbd\AuditLogger;

use Hsnbd\AuditLogger\Commands\BootstrapLogServer;
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
                BootstrapLogServer::class
            ]);

            $this->publishes([
                $this->getConfigFilePath() => config_path($this->packageAlias . '.php'),
            ], 'config');
        }

        //TODO: It seems boot method run twice.

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
