<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Http;

use FD\LogViewer\Service\File\Http\HttpAccessLineParser;
use FD\LogViewer\Service\File\LogLineParserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HttpAccessLineParser::class)]
class HttpAccessLineParserTest extends TestCase
{
    private HttpAccessLineParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new HttpAccessLineParser(null);
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
        $line = '192.168.0.1 - - [01/Jan/2020:15:15:00 +0000] "GET /log-viewer/api/logs HTTP/1.1" 200 12345 ' .
            '"http://example.com/log-viewer/log?file=5d155a6b" "Mozilla/5.0"';

        $expected = [
            'date'     => '01/Jan/2020:15:15:00 +0000',
            'severity' => '200',
            'channel'  => '',
            'message'  => 'GET /log-viewer/api/logs',
            'context'  => [
                'ip'             => '192.168.0.1',
                'identity'       => '-',
                'remote_user'    => '-',
                'http_version'   => 'HTTP/1.1',
                'status_code'    => '200',
                'content_length' => '12345',
                'referrer'       => 'http://example.com/log-viewer/log?file=5d155a6b',
                'user_agent'     => 'Mozilla/5.0',
            ],
            'extra'    => '',
        ];

        $result = $this->parser->parse($line);
        static::assertSame($expected, $result);
    }
}
