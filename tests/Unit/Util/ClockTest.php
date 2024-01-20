<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Util;

use FD\LogViewer\Util\Clock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Clock::class)]
class ClockTest extends TestCase
{
    public function testNow(): void
    {
        $clock = new Clock();
        static::assertEqualsWithDelta(time(), $clock->now()->getTimestamp(), 5);
    }
}
