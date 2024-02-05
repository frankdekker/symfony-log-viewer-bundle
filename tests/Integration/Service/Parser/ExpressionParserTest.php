<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Integration\Service\Parser;

use DateTimeImmutable;
use Exception;
use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\DateParser;
use FD\LogViewer\Service\Parser\ExpressionParser;
use FD\LogViewer\Service\Parser\KeyValueParser;
use FD\LogViewer\Service\Parser\QuotedStringParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\TermParser;
use FD\LogViewer\Service\Parser\WordParser;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class ExpressionParserTest extends TestCase
{
    private ExpressionParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $stringParser = new StringParser(new QuotedStringParser(), new WordParser());
        $this->parser = new ExpressionParser(new TermParser($stringParser, new DateParser(), new KeyValueParser($stringParser)));
    }

    /**
     * @throws Exception
     */
    public function testParseSingleWord(): void
    {
        $expected = new Expression([new WordTerm('foobar', WordTerm::TYPE_INCLUDE)]);
        $actual   = $this->parser->parse(new StringReader('foobar'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseDoubleWords(): void
    {
        $expected = new Expression(
            [
                new WordTerm('foo', WordTerm::TYPE_INCLUDE),
                new WordTerm('bar', WordTerm::TYPE_INCLUDE)
            ]
        );
        $actual   = $this->parser->parse(new StringReader('"foo" bar'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseQuotedString(): void
    {
        $expected = new Expression([new WordTerm('foo bar', WordTerm::TYPE_INCLUDE)]);
        $actual   = $this->parser->parse(new StringReader('"foo bar"'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseDateRange(): void
    {
        $expected = new Expression(
            [
                new DateAfterTerm(new DateTimeImmutable('2020-01-10T00:00')),
                new DateBeforeTerm(new DateTimeImmutable('2020-01-10T10:00'))
            ]
        );
        $actual   = $this->parser->parse(new StringReader('after:2020-01-10T00:00 before:"2020-01-10 10:00"'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseChannels(): void
    {
        $expected = new Expression([new ChannelTerm(['app', 'request'])]);
        $actual   = $this->parser->parse(new StringReader('channel:app|request'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseSeverity(): void
    {
        $expected = new Expression([new SeverityTerm(['warning', 'error'])]);
        $actual   = $this->parser->parse(new StringReader('severity:warning|error'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseIncludeExcludeWord(): void
    {
        $expected = new Expression(
            [
                new WordTerm('foo', WordTerm::TYPE_EXCLUDE),
                new WordTerm('bar', WordTerm::TYPE_INCLUDE),
            ]
        );
        $actual   = $this->parser->parse(new StringReader('exclude:"foo" bar'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseSearchContext(): void
    {
        $expected = new Expression(
            [
                new KeyValueTerm(KeyValueTerm::TYPE_CONTEXT, null, 'baz'),
                new KeyValueTerm(KeyValueTerm::TYPE_CONTEXT, ['foo'], 'bar'),
            ]
        );
        $actual   = $this->parser->parse(new StringReader('context:baz context:foo="bar"'));

        static::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testParseSearchExtra(): void
    {
        $expected = new Expression(
            [
                new KeyValueTerm(KeyValueTerm::TYPE_EXTRA, null, 'baz'),
                new KeyValueTerm(KeyValueTerm::TYPE_EXTRA, ['foo'], 'bar'),
            ]
        );
        $actual   = $this->parser->parse(new StringReader('extra:baz extra:foo="bar"'));

        static::assertEquals($expected, $actual);
    }
}
