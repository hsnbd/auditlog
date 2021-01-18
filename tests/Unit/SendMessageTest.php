<?php

namespace Hsnbd\AuditLogger\Tests\Unit;

use Hsnbd\AuditLogger\Tests\TestCase;

class SendMessageTest extends TestCase
{
    public function testBasicTest()
    {
        $this->assertEquals('Hello', (new \Hsnbd\AuditLogger\AuditLog)->debug('Hello'));
    }
}