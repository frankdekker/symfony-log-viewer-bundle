<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Apache;

use FD\LogViewer\Service\File\Apache\ApacheErrorLineParser;
use FD\LogViewer\Service\File\LogLineParserInterface;
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
        $line = '[Fri Jan 01 12:34:56.498215 2020] [proxy_fcgi:error] [pid 10000] (70007)The timeout specified has expired: ' .
            '[client 192.168.0.1:65344] AH01075: Error dispatching request to : (polling), referer: http://example.com/referer';

        $expected = [
            'date'     => 'Fri Jan 01 12:34:56.498215 2020',
            'severity' => 'error',
            'channel'  => '',
            'message'  => 'AH01075: Error dispatching request to : (polling)',
            'context'  => [
                'module'       => 'proxy_fcgi',
                'pid'          => '10000',
                'error_status' => '(70007)The timeout specified has expired:',
                'ip'           => '192.168.0.1',
                'port'         => '65344',
                'referer'      => 'http://example.com/referer',
            ],
            'extra'    => '',
        ];

        $result = $this->parser->parse($line);
        static::assertSame($expected, $result);
    }
}
