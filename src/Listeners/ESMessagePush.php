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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            $hosts = [
                [
                    'host' => '180.148.214.184',
                    'port' => 9200,
                    'scheme' => 'http',
//                    'user' => 'username',
//                    'pass' => 'password'
                ]
            ];

            $singleHandler  = ClientBuilder::singleHandler();

            $client = ClientBuilder::create()
                ->setHandler($singleHandler)
                ->setHosts($hosts)
                ->build();
            Log::debug($client);

            $params = [
                'index' => 'my_index',
                'pipeline' => 'application_audit_pipeline',
                'body'  => $event->logger->data
            ];

            $response = $client->index($params);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }
    }
}
