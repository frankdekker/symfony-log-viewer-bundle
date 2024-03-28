<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use ArrayIterator;
use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordCollection::class)]
class LogRecordCollectionTest extends TestCase
{
    use AccessorPairAsserter;

    public function testAccessorPairs(): void
    {
        static::assertAccessorPairs(LogRecordCollection::class);
    }

    public function testAddGetLine(): void
    {
        $record = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $index  = new LogRecordCollection(new ArrayIterator([$record]), null);
        static::assertSame([$record], $index->getRecords());
    }
}
