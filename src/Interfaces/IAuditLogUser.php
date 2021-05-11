<?php


namespace Hsnbd\AuditLogger\Interfaces;


interface IAuditLogUser
{
    public function getAuditLogUserMetaData(): array;
}