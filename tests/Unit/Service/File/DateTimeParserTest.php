<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use FD\LogViewer\Service\File\DateTimeParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateTimeParser::class)]
class DateTimeParserTest extends TestCase
{
    #[TestWith([null, '2009-02-13 21:31:30 -0200', 1234567890])]
    #[TestWith([null, 'foobar', null])]
    #[TestWith(['Y-m-d H:i:s O', '2009-02-13 21:31:30 -0200', 1234567890])]
    #[TestWith(['Y-m-d H:i:s O', 'foobar', null])]
    public function testParse(?string $dateFormat, string $date, ?int $expectedTimestamp): void
    {
        $parser = new DateTimeParser($dateFormat);
        static::assertSame($expectedTimestamp, $parser->parse($date));
    }
}
