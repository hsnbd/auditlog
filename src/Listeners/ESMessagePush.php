<?php

namespace Hsnbd\AuditLogger\Listeners;

use Hsnbd\AuditLogger\Classes\HSNElasticSearch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ESMessagePush implements ShouldQueue
{
    public string $connection = 'database';
    public string $queue = 'listeners';
    public int $delay = 1;
    public int $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        try {
            $indexName = config('audit-logger.es.index.name');
            $ingestPipeline = config('audit-logger.es.index.pipeline');

            if (empty($indexName)) {
                throw new \Exception('Index name should not be empty. Please add index name in audit-logger config file.');
            }

            $client = HSNElasticSearch::getClient();
            $params = [
                'index' => $indexName,
                'body' => $event->logData
            ];

            if (!empty($ingestPipeline)) {
                $params['pipeline'] = $ingestPipeline;
            }

            $response = $client->index($params);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            throw new \Exception('Unable to process es queue.');
        }
    }
}