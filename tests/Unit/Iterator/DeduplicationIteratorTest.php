<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Iterator\DeduplicationIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DeduplicationIterator::class)]
class DeduplicationIteratorTest extends TestCase
{
    public function testWithDeduplication(): void
    {
        $recordA1 = new LogRecord('id1', 111, 'debug', 'request', 'message', [], []);
        $recordB1 = new LogRecord('id2', 111, 'debug', 'request', 'message', [], []);
        $recordA2 = new LogRecord('id1', 111, 'debug', 'request', 'message', [], []);
        $recordB2 = new LogRecord('id2', 111, 'debug', 'request', 'message', [], []);

        $iterator = new DeduplicationIterator(new ArrayIterator([$recordA1, $recordB1, $recordA2, $recordB2]));

        static::assertSame([$recordA1, $recordB1], iterator_to_array($iterator));
    }

    public function testWithoutDeduplication(): void
    {
        $recordA = new LogRecord('id1', 111, 'debug', 'request', 'message', [], []);
        $recordB = new LogRecord('id2', 111, 'debug', 'request', 'message', [], []);
        $recordC = new LogRecord('id3', 111, 'debug', 'request', 'message', [], []);
        $recordD = new LogRecord('id4', 111, 'debug', 'request', 'message', [], []);

        $iterator = new DeduplicationIterator(new ArrayIterator([$recordA, $recordB, $recordC, $recordD]));

        static::assertSame([$recordA, $recordB, $recordC, $recordD], iterator_to_array($iterator));
    }
}
