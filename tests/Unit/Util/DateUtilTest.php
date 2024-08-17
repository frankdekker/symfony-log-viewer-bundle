<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Util;

use DateTimeZone;
use Exception;
use FD\LogViewer\Util\DateUtil;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateUtil::class)]
class DateUtilTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[TestWith([null, 'America/New_York', new DateTimeZone('America/New_York')])]
    #[TestWith(['Europe/Amsterdam', 'America/New_York', new DateTimeZone('Europe/Amsterdam')])]
    #[TestWith(['Foobar', 'America/New_York', new DateTimeZone('America/New_York')])]
    public function testTryParseTimezone(?string $timezone, string $default, DateTimeZone $expected): void
    {
        static::assertEquals($expected, DateUtil::tryParseTimezone($timezone, $default));
    }
}
