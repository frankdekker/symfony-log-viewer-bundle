<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Iterator;

use ArrayIterator;
use FD\SymfonyLogViewerBundle\Iterator\LimitIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LimitIterator::class)]
class LimitIteratorTest extends TestCase
{
    public function testGetIteratorWithLimitReached(): void
    {
        $iterator      = new ArrayIterator(['foo' => 'bar', 'foz' => 'baz', 111 => 222]);
        $limitIterator = new LimitIterator($iterator, 2);
        static::assertSame(['foo' => 'bar', 'foz' => 'baz'], iterator_to_array($limitIterator));
    }

    public function testGetIteratorWithoutLimitReached(): void
    {
        $iterator      = new ArrayIterator(['foo' => 'bar', 'foz' => 'baz', 111 => 222]);
        $limitIterator = new LimitIterator($iterator, 5);
        static::assertSame(['foo' => 'bar', 'foz' => 'baz', 111 => 222], iterator_to_array($limitIterator));
    }
}
