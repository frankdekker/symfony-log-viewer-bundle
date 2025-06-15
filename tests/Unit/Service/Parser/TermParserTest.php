<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Expression\LineAfterTerm;
use FD\LogViewer\Entity\Expression\LineBeforeTerm;
use FD\LogViewer\Entity\Expression\MessageTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\DateParser;
use FD\LogViewer\Service\Parser\KeyValueParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\TermParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(TermParser::class)]
class TermParserTest extends TestCase
{
    private StringParser&MockObject   $stringParser;
    private DateParser&MockObject     $dateParser;
    private KeyValueParser&MockObject $keyValueParser;
    private TermParser                $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringParser   = $this->createMock(StringParser::class);
        $this->dateParser     = $this->createMock(DateParser::class);
        $this->keyValueParser = $this->createMock(KeyValueParser::class);
        $this->parser         = new TermParser($this->stringParser, $this->dateParser, $this->keyValueParser);
    }

    /**
     * @throws Exception
     */
    public function testParseBeforeDate(): void
    {
        $string = new StringReader("   before:2020-01-01");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('2020-01-01');
        $this->dateParser->expects(self::once())->method('toDateTimeImmutable')
            ->with('2020-01-01', new DateTimeZone('America/New_York'))
            ->willReturn(new DateTimeImmutable('2020-01-01'));

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
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
        $this->dateParser->expects(self::once())->method('toDateTimeImmutable')
            ->with('2020-01-01', new DateTimeZone('America/New_York'))
            ->willReturn(new DateTimeImmutable('2020-01-01'));

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
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

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
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

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
        static::assertInstanceOf(ChannelTerm::class, $term);
        static::assertSame(['app', 'request'], $term->channels);
    }

    /**
     * @throws Exception
     */
    public function testParseMessage(): void
    {
        $string = new StringReader("   message:foobar");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('foobar');

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
        static::assertInstanceOf(MessageTerm::class, $term);
        static::assertSame('foobar', $term->searchQuery);
    }

    /**
     * @throws Exception
     */
    public function testParseExcludeWord(): void
    {
        $string = new StringReader("   exclude:foobar");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('foobar');

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
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

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
        static::assertInstanceOf(WordTerm::class, $term);
        static::assertSame('foobar', $term->string);
        static::assertSame(WordTerm::TYPE_INCLUDE, $term->type);
    }

    /**
     * @throws Exception
     */
    public function testParseContext(): void
    {
        $string = new StringReader("   context:key=value");
        $term   = new KeyValueTerm('context', ['key'], 'value');

        $this->keyValueParser->expects(self::once())->method('parse')->with('context', $string)->willReturn($term);

        static::assertSame($term, $this->parser->parse($string, new DateTimeZone('America/New_York')));
    }

    /**
     * @throws Exception
     */
    public function testParseExtra(): void
    {
        $string = new StringReader("   extra:key=value");
        $term   = new KeyValueTerm('extra', ['key'], 'value');

        $this->keyValueParser->expects(self::once())->method('parse')->with('extra', $string)->willReturn($term);

        static::assertSame($term, $this->parser->parse($string, new DateTimeZone('America/New_York')));
    }

    /**
     * @throws Exception
     */
    public function testParseLineBefore(): void
    {
        $string = new StringReader("   line-before:5");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('5');

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
        static::assertInstanceOf(LineBeforeTerm::class, $term);
        static::assertSame(5, $term->lines);
    }

    /**
     * @throws Exception
     */
    public function testParseLineAfter(): void
    {
        $string = new StringReader("   line-after:5");

        $this->stringParser->expects(self::once())->method('parse')->with($string)->willReturn('5');

        $term = $this->parser->parse($string, new DateTimeZone('America/New_York'));
        static::assertInstanceOf(LineAfterTerm::class, $term);
        static::assertSame(5, $term->lines);
    }
}
