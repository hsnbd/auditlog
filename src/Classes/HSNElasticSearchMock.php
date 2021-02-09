<?php

namespace Hsnbd\AuditLogger\Classes;

use Elasticsearch\ClientBuilder;
use GuzzleHttp\Ring\Client\MockHandler;

class HSNElasticSearchMock
{
    private static \Elasticsearch\Client $client;

    private final function  __construct() {
        throw new \Exception('initializes only once');
    }

    /**
     * @return \Elasticsearch\Client
     */
    public final static function getClient(): \Elasticsearch\Client
    {
        if(!isset(self::$client)) {
            // The connection class requires 'body' to be a file stream handle
            // Depending on what kind of request you do, you may need to set more values here
            $singleHandler = new MockHandler([
                'status' => 200,
                'transfer_stats' => [
                    'total_time' => 100
                ],
                'body' => [],
                'effective_url' => 'localhost'
            ]);

            self::$client = ClientBuilder::create()
                ->setHandler($singleHandler)
                ->setHosts(config('audit-logger.es.hosts'))
                ->build();
        }
        return self::$client;
    }
}