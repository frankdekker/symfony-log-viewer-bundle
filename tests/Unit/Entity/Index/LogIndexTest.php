<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use ArrayIterator;
use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogIndexIterator::class)]
class LogIndexTest extends TestCase
{
    use AccessorPairAsserter;

    public function testAccessorPairs(): void
    {
        static::assertAccessorPairs(LogIndexIterator::class);
    }

    public function testAddGetLine(): void
    {
        $record = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $index  = new LogIndexIterator(new ArrayIterator([$record]), null);
        static::assertSame([$record], $index->getRecords());
    }
}
