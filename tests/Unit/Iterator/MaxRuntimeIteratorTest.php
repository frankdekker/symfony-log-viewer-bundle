<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use DateTimeImmutable;
use FD\LogViewer\Iterator\MaxRuntimeException;
use FD\LogViewer\Iterator\MaxRuntimeIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

#[CoversClass(MaxRuntimeIterator::class)]
class MaxRuntimeIteratorTest extends TestCase
{
    private ClockInterface&MockObject $clock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock
            ->method('now')
            ->willReturn(
                new DateTimeImmutable('2020-01-01 00:00:00'),
                new DateTimeImmutable('2020-01-01 00:00:15'),
                new DateTimeImmutable('2020-01-01 00:00:30'),
                new DateTimeImmutable('2020-01-01 00:00:45'),
            );
    }

    public function testGetIteratorShouldNotThrow(): void
    {
        $iterator = new MaxRuntimeIterator($this->clock, new ArrayIterator([1, 2, 3]), 1000, false);
        static::assertSame([1, 2, 3], iterator_to_array($iterator));
    }

    public function testGetIteratorShouldStopSilently(): void
    {
        // stop running after 20 seconds
        $iterator = new MaxRuntimeIterator($this->clock, new ArrayIterator([1, 2, 3]), 20, false);
        static::assertSame([1, 2], iterator_to_array($iterator));
    }

    public function testGetIteratorShouldThrow(): void
    {
        // throw exception after 20 seconds
        $iterator = new MaxRuntimeIterator($this->clock, new ArrayIterator([1, 2, 3]), 20, true);

        $this->expectException(MaxRuntimeException::class);
        iterator_to_array($iterator);
    }
}
