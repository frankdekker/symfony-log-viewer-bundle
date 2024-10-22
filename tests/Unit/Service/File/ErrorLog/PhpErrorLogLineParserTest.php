<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\ErrorLog;

use FD\LogViewer\Service\File\ErrorLog\PhpErrorLogLineParser;
use FD\LogViewer\Service\File\LogLineParserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhpErrorLogLineParser::class)]
class PhpErrorLogLineParserTest extends TestCase
{
    private PhpErrorLogLineParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new PhpErrorLogLineParser(null);
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
        $line = '[01-Jan-2024 12:34:56 UTC] test message';

        $expected = [
            'date'     => '01-Jan-2024 12:34:56 UTC',
            'severity' => 'error',
            'channel'  => '',
            'message'  => 'test message',
            'context'  => '',
            'extra'    => '',
        ];

        $result = $this->parser->parse($line);
        static::assertSame($expected, $result);
    }
}
