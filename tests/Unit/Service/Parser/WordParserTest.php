<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\WordParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WordParser::class)]
class WordParserTest extends TestCase
{
    private WordParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new WordParser();
    }

    public function testParse(): void
    {
        $reader = new StringReader("word1 word2");
        static::assertSame("word1", $this->parser->parse($reader));

        $reader->skipWhitespace();
        static::assertSame("word2", $this->parser->parse($reader));
    }
}
