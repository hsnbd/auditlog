<?php

namespace Hsnbd\AuditLogger\Listeners;

use Elasticsearch\ClientBuilder;
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
     */
    public function handle($event)
    {
        try {
            $singleHandler = ClientBuilder::singleHandler();

            $client = ClientBuilder::create()
                ->setHandler($singleHandler)
                ->setHosts(config('audit-logger.es.hosts'))
                ->build();

            $params = [
                'index' => 'my_index',
                'body' => $event->logger->data
            ];
            if (config('audit-logger.es.app-audit-pipeline')) {
                $params['pipeline'] = config('audit-logger.es.app-audit-pipeline');
            }

            $response = $client->index($params);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }
    }
}
