<?php

namespace Hsnbd\AuditLogger\Commands;

use Hsnbd\AuditLogger\HSNElasticSearch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PingAuditLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditlog:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test elastic search connection';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->alert('Start bootstrapping for Elasticsearch.');

        try {
            $this->info('Establishing Elasticsearch connection...');
            $client = HSNElasticSearch::getClient();
            $response = $client->ping([]);
            if ($response) {
                $this->info('Elasticsearch successfully connected.');
            } else {
                $this->line('<fg=red>Elasticsearch connection error. Contact support.</>');
            }
        } catch (\Throwable $exception) {
            $this->line('<fg=red>Elasticsearch connection error. Log: (' . $exception->getMessage() . ')</>');
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return 0;
        }

        $this->alert('Please confirm before start logging.');
        $this->table(
            ['SL#', 'Requirement'],
            [
                ['1.', 'php artisan queue:table'],
                ['2.', 'php artisan migrate'],
                ['3.', 'php artisan queue:work database --queue=listeners'],
                ['4.', 'make sure elasticsearch server is running and discoverable'],
                ['5.', 'Please create a policy for index lifecycle.'],
                ['6.', 'Please create an ingest pipeline to enhancing result'],
                ['7.', 'Please create a template for index for better performance'],
                ['8.', 'Please create a snapshot of index for backup'],
            ]
        );

        return 1;
    }
}
