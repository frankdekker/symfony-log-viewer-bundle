<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\QuotedStringParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\WordParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringParser::class)]
class StringParserTest extends TestCase
{
    private QuotedStringParser&MockObject $quotedStringParser;
    private WordParser&MockObject $wordParser;
    private StringParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quotedStringParser = $this->createMock(QuotedStringParser::class);
        $this->wordParser         = $this->createMock(WordParser::class);
        $this->parser             = new StringParser($this->quotedStringParser, $this->wordParser);
    }

    public function testParse(): void
    {
        $string = new StringReader('foo');
        $this->quotedStringParser->expects(static::never())->method('parse');
        $this->wordParser->expects(static::once())->method('parse')->with($string)->willReturn('bar');

        static::assertSame('bar', $this->parser->parse($string));
    }

    public function testParseWithSingleQuote(): void
    {
        $string = new StringReader("'foo'");
        $this->quotedStringParser->expects(static::once())->method('parse')->with($string, "'", '\\')->willReturn('bar');
        $this->wordParser->expects(static::never())->method('parse');

        static::assertSame('bar', $this->parser->parse($string));
    }

    public function testParseWithDoubleQuote(): void
    {
        $string = new StringReader('"foo"');
        $this->quotedStringParser->expects(static::once())->method('parse')->with($string, '"', '\\')->willReturn('bar');
        $this->wordParser->expects(static::never())->method('parse');

        static::assertSame('bar', $this->parser->parse($string));
    }
}
