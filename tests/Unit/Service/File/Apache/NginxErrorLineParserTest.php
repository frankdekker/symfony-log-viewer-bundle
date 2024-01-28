<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Nginx;

use FD\LogViewer\Service\File\LogLineParserInterface;
use FD\LogViewer\Service\File\Nginx\ApacheErrorLineParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApacheErrorLineParser::class)]
class ApacheErrorLineParserTest extends TestCase
{
    private ApacheErrorLineParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ApacheErrorLineParser(null);
    }

    public function testMatches(): void
    {
        static::assertSame(LogLineParserInterface::MATCH_START, $this->parser->matches('line'));
    }

    public function testParseNoMatch(): void
    {
        static::assertNull($this->parser->parse('foobar'));
    }

    public function testParse(): void
    {
        $line = '2020/01/01 12:34:56 [error] 21#21: *11 upstream timed out (110: Operation timed out) while reading response header from upstream' .
            ', client: 192.168.0.1, server: , request: "GET /log-viewer/log HTTP/1.1", upstream: "fastcgi://172.21.0.2:9000", ' .
            'host: "example.com:8888"';

        $expected = [
            'date'     => '2020/01/01 12:34:56',
            'severity' => 'error',
            'channel'  => '',
            'message'  => '*11 upstream timed out (110: Operation timed out) while reading response header from upstream',
            'context'  => [
                'ip'       => '192.168.0.1',
                'request'  => 'GET /log-viewer/log HTTP/1.1',
                'upstream' => 'fastcgi://172.21.0.2:9000',
                'host'     => 'example.com:8888',
            ],
            'extra'    => '',
        ];

        $result = $this->parser->parse($line);
        static::assertSame($expected, $result);
    }
}
