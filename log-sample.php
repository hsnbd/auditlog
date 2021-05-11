<?php

$log = [
    'application_meta' => [
        'application_name' => '',
        'server_ip' => ''
    ],
    'log_meta' => [
        'log_url' => '',
        'log_type' => '',
        'action_model' => '',
        'action_table' => '',
        'action_id' => '',
        'operation_type' => '',
    ],
    'log_data' => [],
    'user_meta' => [],
    'client_ip' => '',
    'browser' => '',
    'message' => '',
];

$user = new stdClass();

function auditLog($message, $data, $logMeta, $userMeta, $applicationMeta)
{

}