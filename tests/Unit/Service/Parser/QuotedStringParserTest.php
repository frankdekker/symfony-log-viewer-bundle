<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\QuotedStringParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(QuotedStringParser::class)]
class QuotedStringParserTest extends TestCase
{
    private QuotedStringParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new QuotedStringParser();
    }

    public function testParseWithSingleQuote(): void
    {
        $reader = new StringReader("'word 1' bar");
        static::assertSame("word 1", $this->parser->parse($reader, "'", '\\'));
    }

    public function testParseWithDoubleQuote(): void
    {
        $reader = new StringReader('"word 1" bar');
        static::assertSame("word 1", $this->parser->parse($reader, '"', '\\'));
    }

    public function testParseWithDifferentQuoteType(): void
    {
        $reader = new StringReader('"word \'1\'" bar');
        static::assertSame("word '1'", $this->parser->parse($reader, '"', '\\'));
    }

    public function testParseEscapeChar(): void
    {
        $reader = new StringReader('"word\\\\" bar');

        static::assertSame("word\\\\", $this->parser->parse($reader, '"', '\\'));
    }

    public function testParseEscapeQuote(): void
    {
        $reader = new StringReader('"word\\"" bar');
        static::assertSame("word\\\"", $this->parser->parse($reader, '"', '\\'));
    }
}
