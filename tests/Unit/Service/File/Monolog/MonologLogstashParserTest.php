<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Monolog;

use FD\LogViewer\Service\File\LogLineParserInterface;
use FD\LogViewer\Service\File\Monolog\MonologLogstashParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MonologLogstashParser::class)]
class MonologLogstashParserTest extends TestCase
{
    private MonologLogstashParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new MonologLogstashParser();
    }

    public function testMatches(): void
    {
        static::assertSame(LogLineParserInterface::MATCH_START, $this->parser->matches('line'));
    }

    public function testParseInvalidJson(): void
    {
        static::assertNull($this->parser->parse('invalid json'));
    }

    public function testParseNonArrayJson(): void
    {
        static::assertNull($this->parser->parse('"string"'));
    }

    public function testParse(): void
    {
        $json     = '{"@timestamp":"2021-01-01 00:00:00.000000+00:00","@version":1,' .
            '"host":"my-host","message":"message","type":"app","channel":"app","level":"INFO",' .
            '"monolog_level":200,"context":["context"],"extra":["extra"]}';

        $expected = [
            'date'     => '2021-01-01 00:00:00.000000+00:00',
            'severity' => 'INFO',
            'channel'  => 'app',
            'message'  => 'message',
            'context'  => ['context'],
            'extra'    => ['extra'],
        ];
        static::assertSame($expected, $this->parser->parse($json));
    }
}
