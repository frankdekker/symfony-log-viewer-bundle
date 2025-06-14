<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use ArrayIterator;
use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Output\DirectionEnum;
use LogicException;
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

    public function testGetRecords(): void
    {
        $record     = new LogRecord('id', 'record', 111111, 'debug', 'request', 'message', [], []);
        $collection = new LogRecordCollection(new ArrayIterator([$record]), null);
        static::assertSame([$record], $collection->getRecords());

        // a second time should result in the same records
        static::assertSame([$record], $collection->getRecords());
    }

    public function testGetRecordsViaIterator(): void
    {
        $record     = new LogRecord('id', 'record', 111111, 'debug', 'request', 'message', [], []);
        $collection = new LogRecordCollection(new ArrayIterator([$record]), null);
        static::assertSame([$record], iterator_to_array($collection));

        // a second time should result in the same records
        static::assertSame([$record], iterator_to_array($collection));
    }

    public function testGetPaginatorBeforeIteration(): void
    {
        $collection = new LogRecordCollection(new ArrayIterator([]), null);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot get paginator before records are read');
        $collection->getPaginator();
    }

    public function testGetPaginatorGetPaginator(): void
    {
        $paginator = new Paginator(DirectionEnum::Asc, true, true, 0);

        $collection = new LogRecordCollection(new ArrayIterator([]), static fn() => $paginator);

        // force iteration
        $collection->getRecords();

        // then get paginator
        static::assertSame($paginator, $collection->getPaginator());
    }
}
