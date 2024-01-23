<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use Exception;
use FD\LogViewer\Entity\Parser\DateAfterTerm;
use FD\LogViewer\Entity\Parser\DateBeforeTerm;
use FD\LogViewer\Entity\Parser\StringTerm;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\StringReader;
use FD\LogViewer\Service\Parser\TermParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(TermParser::class)]
class TermParserTest extends TestCase
{
    private StringParser&MockObject $stringParser;
    private TermParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringParser = $this->createMock(StringParser::class);
        $this->parser       = new TermParser($this->stringParser);
    }

    /**
     * @throws Exception
     */
    public function testParseBeforeDate(): void
    {
        $string = new StringReader("   before:2020-01-01");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('2020-01-01');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(DateBeforeTerm::class, $term);
        static::assertSame('2020-01-01', $term->date->format('Y-m-d'));
    }

    /**
     * @throws Exception
     */
    public function testParseAfterDate(): void
    {
        $string = new StringReader("   after:2020-01-01");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('2020-01-01');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(DateAfterTerm::class, $term);
        static::assertSame('2020-01-01', $term->date->format('Y-m-d'));
    }

    /**
     * @throws Exception
     */
    public function testParseString(): void
    {
        $string = new StringReader("   foobar");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('foobar');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(StringTerm::class, $term);
        static::assertSame('foobar', $term->string);
    }
}
