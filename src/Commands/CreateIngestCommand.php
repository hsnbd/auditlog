<?php

namespace Hsnbd\AuditLogger\Commands;

use Hsnbd\AuditLogger\HSNElasticSearch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateIngestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditlog:ingest 
    {type : Create, Update or Delete ingest} 
    {name : The name of ingest. (It will read from config file)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Managing Elk Ingest.';

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
        $allowedOperationTypes = ['create', 'update', 'delete'];

        $operationType = $this->argument('type');

        if (!array_search($operationType, $allowedOperationTypes, true)) {
            $this->line('<fg=red>Did you mean create/update/delete?</>');
            return 0;
        }

        $ingestName = $this->argument('ingest');
        $allIngestFromConfig = config('audit-logger.es.ingest');

        if (empty($allIngestFromConfig[$ingestName])) {
            $this->line('<fg=red>Please define ingest in config file.</>');
            return 0;
        }
        $ingestConfigArray = $allIngestFromConfig[$ingestName];

        try {
            $client = HSNElasticSearch::getClient();
            $client->ingest()->putPipeline($ingestConfigArray);
            $this->info('Elasticsearch Ingest successfully connected.');
        } catch (\Throwable $exception) {
            $this->line('<fg=red>Error occurred. Log: (' . $exception->getMessage() . ')</>');
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return 0;
        }

        return 1;
    }
}
