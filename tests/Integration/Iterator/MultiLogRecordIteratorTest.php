<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Integration\Iterator;

use ArrayIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Iterator\MultiLogRecordIterator;
use FD\LogViewer\Service\File\LogRecordDateComparator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiLogRecordIterator::class)]
class MultiLogRecordIteratorTest extends TestCase
{
    private LogRecordDateComparator $comparator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->comparator = new LogRecordDateComparator(DirectionEnum::Asc);
    }

    public function testTwoStreamsSingleItem(): void
    {
        $recordA = new LogRecord('id', 1000, 'error', 'channel', 'message', [], []);
        $recordB = new LogRecord('id', 2000, 'error', 'channel', 'message', [], []);

        $iteratorA = new ArrayIterator([$recordA]);
        $iteratorB = new ArrayIterator([$recordB]);

        $recordIterator = new MultiLogRecordIterator([$iteratorA, $iteratorB], $this->comparator);
        $results        = iterator_to_array($recordIterator);

        static::assertSame([$recordA, $recordB], $results);
    }

    public function testTwoEqualSizedIterators(): void
    {
        $recordA = new LogRecord('id', 1000, 'error', 'channel', 'message', [], []);
        $recordB = new LogRecord('id', 2000, 'error', 'channel', 'message', [], []);
        $recordC = new LogRecord('id', 3000, 'error', 'channel', 'message', [], []);
        $recordD = new LogRecord('id', 4000, 'error', 'channel', 'message', [], []);

        $iteratorA = new ArrayIterator([$recordA, $recordC]);
        $iteratorB = new ArrayIterator([$recordB, $recordD]);

        $recordIterator = new MultiLogRecordIterator([$iteratorA, $iteratorB], $this->comparator);
        $results        = iterator_to_array($recordIterator);

        static::assertSame([$recordA, $recordB, $recordC, $recordD], $results);
    }

    public function testTwoUnevenSizedIterators(): void
    {
        $recordA = new LogRecord('id', 1000, 'error', 'channel', 'message', [], []);
        $recordB = new LogRecord('id', 2000, 'error', 'channel', 'message', [], []);
        $recordC = new LogRecord('id', 3000, 'error', 'channel', 'message', [], []);
        $recordD = new LogRecord('id', 4000, 'error', 'channel', 'message', [], []);

        $iteratorA = new ArrayIterator([$recordB]);
        $iteratorB = new ArrayIterator([$recordA, $recordC, $recordD]);

        $recordIterator = new MultiLogRecordIterator([$iteratorA, $iteratorB], $this->comparator);
        $results        = iterator_to_array($recordIterator);

        static::assertSame([$recordA, $recordB, $recordC, $recordD], $results);
    }
}
