<?php

namespace Hsnbd\AuditLogger\Interfaces;


use Illuminate\Database\Eloquent\Model;

interface IAuditLogProcessor
{
    public function getModel(): ?Model;

    public function getTimestamp(): string;

    public function getModelActionType(): ?string;

    public function getMessage(): ?string;

    public function getAlertType(): string;

    public function getLogType(): string;

    public function getAuditLogData(): array;

    public function getUserMetaData(): array;

    public function getAuditLogFillableFields(): array;

    public function getBrowserAgent(): ?string;

    public function getIpAddress();
}