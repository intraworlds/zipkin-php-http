<?php
namespace IW\ZipkinPhpHttp\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SpanTest extends TestCase
{

    public function testConstruct() {
        $traceId = '4e441824ec2b6a44ffdc9bb9a6453df3';

        $span = new Span(Span::KIND_CLIENT, $traceId);
        $this->assertAttributeEquals('CLIENT', 'kind', $span);
        $this->assertAttributeEquals($traceId, 'traceId', $span);
        $this->assertObjectHasAttribute('id', $span);
    }

    public function testConstructInvalidKind() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid kind of span: blabla');
        $span = new Span('blabla', '4e441824ec2b6a44ffdc9bb9a6453df3');
    }

    public function testConstructInvalidTraceId() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid traceId: 4e441824ec2b6');
        $span = new Span(Span::KIND_CLIENT, '4e441824ec2b6');
    }

    public function testSpanning() {
        $span = new Span(Span::KIND_CLIENT, '4e441824ec2b6a44ffdc9bb9a6453df3');
        $span->start(1);
        $span->stop(3);
        $this->assertAttributeEquals(1000000, 'timestamp', $span);
        $this->assertAttributeEquals(2000000, 'duration', $span);

        $span->start();
        usleep(10);
        $span->stop();
        $this->assertAttributeGreaterThanOrEqual(10, 'duration', $span);
    }

}
