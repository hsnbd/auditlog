<?php

namespace Hsnbd\AuditLogger\Commands;

use Hsnbd\AuditLogger\HSNElasticSearch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BootstrapLogServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditlog:bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstrap basic setup for ELK stack.';

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
            $this->info('Elasticsearch successfully connected.');
        } catch (\Throwable $exception) {
            $this->line('<fg=red>Elasticsearch connection error. Log: (' . $exception->getMessage() . ')</>');
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return 0;
        }

        try {
            $this->info('Creating first ES index for log...');

            /***
             * Create an index
             * Now that we are starting fresh (no data or index), let's add a new index with some custom settings:
             */
            $params = [
                'index' => config('audit-logger.es.index.name')
            ];
            $indexConfig = config('audit-logger.es.index.config');
            if (!empty($indexConfig)) {
                $params['body'] = $indexConfig;
            }

            $response = $client->indices()->create($params);
            if ($response && !empty($response['acknowledged']) && $response['acknowledged'] == 1) {
                $this->info('ES index successfully created...');
            } else {
                $this->line('<fg=red>Failed to create ES index. Please contact support</>');
            }
        } catch (\Throwable $exception) {
            $this->line('<fg=red>Failed to create ES index. Log: (' . $exception->getMessage() . ')</>');
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
