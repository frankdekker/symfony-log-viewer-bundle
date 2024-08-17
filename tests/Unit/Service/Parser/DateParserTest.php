<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Parser;

use DateTimeZone;
use FD\LogViewer\Service\Parser\DateParser;
use FD\LogViewer\Service\Parser\InvalidDateTimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateParser::class)]
class DateParserTest extends TestCase
{
    private DateParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new DateParser();
    }

    /**
     * @throws InvalidDateTimeException
     */
    public function testToDateTimeImmutable(): void
    {
        $date = $this->parser->toDateTimeImmutable('2021-01-01 00:00:00', new DateTimeZone('America/New_York'));
        static::assertSame('2021-01-01 00:00:00', $date->format('Y-m-d H:i:s'));
    }

    /**
     * @throws InvalidDateTimeException
     */
    public function testToDateTimeImmutableFailure(): void
    {
        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage('Invalid date');
        $this->parser->toDateTimeImmutable('foobar', new DateTimeZone('America/New_York'));
    }
}
