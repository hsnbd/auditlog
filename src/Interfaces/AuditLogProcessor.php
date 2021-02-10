<?php

namespace Hsnbd\AuditLogger\Interfaces;


interface AuditLogProcessor
{
    public function processAuditLog(): array;

    public function getUserInfo(): array;
}