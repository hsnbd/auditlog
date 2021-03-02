<?php

/**
 * --------------------------------
 * Example hosts
 * ---------------------------------
 * array [
 *      'host' => 'foo.com',
 *      'port' => '9200',
 *      'scheme' => 'https',
 *      'path' => '/elastic',
 *      'user' => 'username',
 *      'pass' => 'password!#$?*abc'
 * ]
 */
return [
    'logProcessor' => \Hsnbd\AuditLogger\Classes\AuditLogProcessor::class,
    'userModel' => \App\Models\User::class,
    'eloquent_event_for_log' => [
//        'eloquent.saved: *',
        'eloquent.created: *',
        'eloquent.updated: *',
        'eloquent.deleted: *',
        'eloquent.restored: *',
    ],
    'es' => [
        'hosts' => [
            [
                'host' => '127.0.0.1',
                'port' => 9200,
                'scheme' => 'http',
            ],
            //[
            //  'host' => 'foo.com',
            //  'port' => '9200',
            //  'scheme' => 'https',
            //  'path' => '/elastic',
            //  'user' => 'username',
            //  'pass' => 'password!#$?*abc'
            //]
        ],
        'index' => [
            'name' => '',
            'pipeline' => '',
            'template' => '',
            'policy' => '',
            'config' => [
                'settings' => [
                ]
            ]
        ],
        'ingest' => [
            'basic_ingest' => [
                'id' => 'basic_ingest',
                'body' => [
                    'description' => 'Extract attachment information',
                    'processors' => [
                        [
                            "lowercase" => [
                                "field" => "user.office"
                            ]
                        ],
                        [
                            "geoip" => [
                                "field" => "ip_addr"
                            ]
                        ],
                        [
                            "user_agent" => [
                                "field" => "browser"
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];