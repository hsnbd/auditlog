<?php

if (!function_exists('auditLog')) {
    function auditLog(string $message = null) {
        $logger = new \AuditLogger\AuditLog();
        return is_null($message) ? $logger : $logger->debug($message);
    }
}
