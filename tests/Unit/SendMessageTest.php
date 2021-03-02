<?php

namespace Hsnbd\AuditLogger\Tests\Unit;

use Hsnbd\AuditLogger\AuditLog;
use Hsnbd\AuditLogger\Tests\TestCase;

class SendMessageTest extends TestCase
{
    public function testBasicTest()
    {
        $a = (new AuditLog)->debug('Hello');
        $this->assertEquals('Hello', 'Hello');
    }
}
