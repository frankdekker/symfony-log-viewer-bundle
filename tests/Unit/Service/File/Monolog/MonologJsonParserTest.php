<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Monolog;

use FD\LogViewer\Service\File\LogLineParserInterface;
use FD\LogViewer\Service\File\Monolog\MonologJsonParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MonologJsonParser::class)]
class MonologJsonParserTest extends TestCase
{
    private MonologJsonParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new MonologJsonParser();
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
        $json     = '{"datetime":"2021-01-01 00:00:00","level_name":"INFO","channel":"app",' .
            '"message":"message","context":["context"],"extra":["extra"]}';
        $expected = [
            'date'     => '2021-01-01 00:00:00',
            'severity' => 'INFO',
            'channel'  => 'app',
            'message'  => 'message',
            'context'  => ['context'],
            'extra'    => ['extra'],
        ];
        static::assertSame($expected, $this->parser->parse($json));
    }
}
