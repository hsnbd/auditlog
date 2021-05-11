<?php

namespace Hsnbd\AuditLogger\Interfaces;


use Illuminate\Database\Eloquent\Model;

interface IAuditLogProcessor
{
    public function setUser(?IAuditLogUser $user): self;

    public function setModel(?Model $model): self;

    public function setModelEventType(?string $modelEvent): self;

    public function setMessage(?string $message): self;

    public function setCustomLogData(?array $data): self;

    public function setLogMetaData(?array $meta): self;

    public function setFillableLogColumns(array $meta): self;

    public function setTimeStamp(string $timestamp): self;

    public function setLogType(string $logType): self;

    public function setAlertType(string $alertType): self;

    public function getAuditLogData(): array;
}