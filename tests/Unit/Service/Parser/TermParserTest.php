<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use DateTimeImmutable;
use Exception;
use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\DateParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\TermParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(TermParser::class)]
class TermParserTest extends TestCase
{
    private StringParser&MockObject $stringParser;
    private DateParser&MockObject $dateParser;
    private TermParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringParser = $this->createMock(StringParser::class);
        $this->dateParser   = $this->createMock(DateParser::class);
        $this->parser       = new TermParser($this->stringParser, $this->dateParser);
    }

    /**
     * @throws Exception
     */
    public function testParseBeforeDate(): void
    {
        $string = new StringReader("   before:2020-01-01");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('2020-01-01');
        $this->dateParser->expects(self::once())->method('toDateTimeImmutable')->with('2020-01-01')->willReturn(new DateTimeImmutable('2020-01-01'));

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
        $this->dateParser->expects(self::once())->method('toDateTimeImmutable')->with('2020-01-01')->willReturn(new DateTimeImmutable('2020-01-01'));

        $term = $this->parser->parse($string);
        static::assertInstanceOf(DateAfterTerm::class, $term);
        static::assertSame('2020-01-01', $term->date->format('Y-m-d'));
    }

    /**
     * @throws Exception
     */
    public function testParseSeverity(): void
    {
        $string = new StringReader("   severity:info|error");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('info|error');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(SeverityTerm::class, $term);
        static::assertSame(['info', 'error'], $term->severities);
    }

    /**
     * @throws Exception
     */
    public function testParseChannel(): void
    {
        $string = new StringReader("   channel:app|request");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('app|request');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(ChannelTerm::class, $term);
        static::assertSame(['app', 'request'], $term->channels);
    }

    /**
     * @throws Exception
     */
    public function testParseExcludeWord(): void
    {
        $string = new StringReader("   exclude:foobar");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('foobar');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(WordTerm::class, $term);
        static::assertSame('foobar', $term->string);
        static::assertSame(WordTerm::TYPE_EXCLUDE, $term->type);
    }

    /**
     * @throws Exception
     */
    public function testParseString(): void
    {
        $string = new StringReader("   foobar");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('foobar');

        $term = $this->parser->parse($string);
        static::assertInstanceOf(WordTerm::class, $term);
        static::assertSame('foobar', $term->string);
        static::assertSame(WordTerm::TYPE_INCLUDE, $term->type);
    }
}
