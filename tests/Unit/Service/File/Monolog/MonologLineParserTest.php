<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Monolog;

use FD\LogViewer\Service\File\LogLineParserInterface;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(MonologLineParser::class)]
class MonologLineParserTest extends TestCase
{
    private MonologLineParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new MonologLineParser(MonologLineParser::START_OF_MESSAGE_PATTERN, MonologLineParser::LOG_LINE_PATTERN);
    }

    #[TestWith(['[2000-01-01T00:00:00.000000+00:00] app.DEBUG: message', LogLineParserInterface::MATCH_START])]
    #[TestWith(['[2000-01-01T00:00:00] app.DEBUG: message', LogLineParserInterface::MATCH_START])]
    #[TestWith(['foobar', LogLineParserInterface::MATCH_APPEND])]
    public function testMatches(string $line, int $expected): void
    {
        static::assertSame($expected, $this->parser->matches($line));
    }

    public function testMatchesWithoutPattern(): void
    {
        $parser = new MonologLineParser(null, MonologLineParser::LOG_LINE_PATTERN);

        static::assertSame(LogLineParserInterface::MATCH_START, $parser->matches('foobar'));
    }

    public function testParseWithContextAndExtra(): void
    {
        $expected = [
            'date'     => '2000-01-01T00:00:00.000',
            'severity' => 'DEBUG',
            'channel'  => 'app',
            'message'  => 'message',
            'context'  => ['context' => '[object]context'],
            'extra'    => ['extra' => '[object]extra'],
        ];

        $result = $this->parser->parse('[2000-01-01T00:00:00.000] app.DEBUG: message {"context":"[object]context"} {"extra":"[object]extra"}' . "\n");
        static::assertSame($expected, $result);
    }

    public function testParseUnparseableContextAndExtra(): void
    {
        $expected = [
            'date'     => '2000-01-01T00:00:00.000',
            'severity' => 'DEBUG',
            'channel'  => 'app',
            'message'  => 'message',
            'context'  => '{"foo"}',
            'extra'    => '{"bar"}',
        ];

        $result = $this->parser->parse('[2000-01-01T00:00:00.000] app.DEBUG: message {"foo"} {"bar"}' . "\n");
        static::assertSame($expected, $result);
    }

    public function testParseNoMatch(): void
    {
        static::assertNull($this->parser->parse('foobar'));
    }
}
