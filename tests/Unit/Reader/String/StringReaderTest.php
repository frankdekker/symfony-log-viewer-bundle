<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Reader\String;

use FD\LogViewer\Reader\String\StringReader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringReader::class)]
class StringReaderTest extends TestCase
{
    public function testReadString(): void
    {
        $string = new StringReader('Foobar  ');

        static::assertSame('F', $string->char());
        static::assertSame('Foob', $string->peek(4));

        $string->next();
        static::assertSame('o', $string->char());
        static::assertSame('ooba', $string->peek(4));

        $string->skip(['o']);
        static::assertSame('b', $string->char());
        static::assertSame('bar ', $string->peek(4));

        $string->skip(['b', 'a', 'r']);
        static::assertSame(' ', $string->char());

        $string->skipWhitespace();
        static::assertTrue($string->eol());
    }

    public function testRead(): void
    {
        $string = new StringReader('foobar');

        static::assertFalse($string->read('baz'));
        static::assertTrue($string->read('foo'));
        static::assertSame('b', $string->char());
    }
}
