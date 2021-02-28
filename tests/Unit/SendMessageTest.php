<?php

namespace Hsnbd\AuditLogger\Tests\Unit;

use Hsnbd\AuditLogger\Tests\TestCase;

class SendMessageTest extends TestCase
{
    public function testBasicTest()
    {
        $a = (new \Hsnbd\AuditLogger\AuditLog)->debug('Hello');
        dd($a);
        $this->assertEquals('Hello', $a);
    }
}
