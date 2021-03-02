<?php

namespace Hsnbd\AuditLogger\Classes;

use Hsnbd\AuditLogger\Interfaces\AuditLogProcessor as AuditLogProcessorInterface;


/**
 * Class AuditLogProcessor
 * @package Hsnbd\AuditLogger
 */
class AuditLogProcessorAdapter implements AuditLogProcessorInterface
{
    private AuditLogProcessorInterface $auditLogProcessor;

    /**
     * AuditLogProcessor constructor.
     * @param AuditLogProcessorInterface $auditLogProcessor
     */
    public function __construct(AuditLogProcessorInterface $auditLogProcessor)
    {
        $this->auditLogProcessor = $auditLogProcessor;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAuditLogData(): array
    {
        return $this->auditLogProcessor->getAuditLogData();
    }

    /**
     * @return array
     */
    public function getUserMetaData(): array
    {
        return $this->auditLogProcessor->getUserMetaData();
    }
}
