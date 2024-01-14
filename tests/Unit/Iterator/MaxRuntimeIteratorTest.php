<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use FD\LogViewer\Iterator\MaxRuntimeException;
use FD\LogViewer\Iterator\MaxRuntimeIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

#[CoversClass(MaxRuntimeIterator::class)]
class MaxRuntimeIteratorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ClockMock::register(MaxRuntimeIterator::class);
        ClockMock::register(static::class);
        ClockMock::withClockMock(true);
    }

    public function testGetIteratorShouldNotThrow(): void
    {
        $iterator = new MaxRuntimeIterator(new ArrayIterator([1, 2, 3]), 1, false);
        static::assertSame([1, 2, 3], iterator_to_array($iterator));
    }

    public function testGetIteratorShouldStopSilently(): void
    {
        $iterator = new MaxRuntimeIterator(new ArrayIterator([1, 2, 3]), 20, false);
        $result   = [];
        foreach ($iterator as $value) {
            // increment time by 15 seconds
            sleep(15);
            $result[] = $value;
        }
        static::assertSame([1, 2], $result);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testGetIteratorShouldThrow(): void
    {
        $iterator = new MaxRuntimeIterator(new ArrayIterator([1, 2, 3]), 20, true);

        $this->expectException(MaxRuntimeException::class);
        foreach ($iterator as $value) {
            // increment time by 15 seconds
            sleep(15);
        }
    }
}
