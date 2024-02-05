<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Integration\Service\Parser;

use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Reader\String\StringReader;
use FD\LogViewer\Service\Parser\KeyValueParser;
use FD\LogViewer\Service\Parser\QuotedStringParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\WordParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(KeyValueParser::class)]
class KeyValueParserTest extends TestCase
{
    private KeyValueParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new KeyValueParser(new StringParser(new QuotedStringParser(), new WordParser()));
    }

    /**
     * @param string[]|null $keys
     */
    #[TestWith(['foobar', null, 'foobar'])]
    #[TestWith(['"foobar"', null, 'foobar'])]
    #[TestWith(['"foo" "bar" ', null, 'foo'])]
    #[TestWith(['foo.bar boo', null, 'foo.bar'])]
    #[TestWith(['foo=bar', ['foo'], 'bar'])]
    #[TestWith(['foo="bar"', ['foo'], 'bar'])]
    #[TestWith(['foo="bar" baz', ['foo'], 'bar'])]
    #[TestWith(['"foo"=bar baz', ['foo'], 'bar'])]
    #[TestWith(['foo.bar=baz', ['foo', 'bar'], 'baz'])]
    #[TestWith(['foo.bar=baz boo', ['foo', 'bar'], 'baz'])]
    #[TestWith(['foo."bar"=baz boo', ['foo', 'bar'], 'baz'])]
    public function testParseString(string $string, ?array $keys, string $value): void
    {
        $term = $this->parser->parse(KeyValueTerm::TYPE_CONTEXT, new StringReader($string));

        static::assertSame($keys, $term->keys);
        static::assertSame($value, $term->value);
    }
}
