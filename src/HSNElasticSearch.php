<?php

namespace Hsnbd\AuditLogger;

use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class HSNElasticSearch
{
    private static \Elasticsearch\Client $client;

    private final function __construct()
    {
        throw new \Exception('initializes only once');
    }

    /**
     * @return \Elasticsearch\Client
     */
    public final static function getClient(): \Elasticsearch\Client
    {
        if (!isset(self::$client)) {
//            $logger = new Logger('UserNamedLogger');
//            $filename = storage_path('logs/hsn-audit-logger.log');
//            $streamHandler = new StreamHandler($filename, \Monolog\Logger::DEBUG, true, 0755);
//            $streamHandler->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, true, true));
//            $logger->pushHandler($streamHandler);

            $singleHandler  = ClientBuilder::singleHandler();
            // $multiHandler   = ClientBuilder::multiHandler();

            $client = ClientBuilder::create();
            $client->setHandler($singleHandler);
            $client->setHosts(config('audit-logger.es.hosts'));
//            $client->setLogger($logger);
            //$client->setRetries(2);

            self::$client = $client->build();
        }
        return self::$client;
    }
}