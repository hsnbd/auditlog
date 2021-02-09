<?php

namespace Hsnbd\AuditLogger\Interfaces;


interface AuditLogProcessor
{
    public function processAuditLog(string $alertType): array;

    public function getUserInfo(): array;
}