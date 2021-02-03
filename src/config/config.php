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
    ]
];