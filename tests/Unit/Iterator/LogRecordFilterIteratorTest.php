<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Iterator;

use ArrayIterator;
use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordFilterIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordFilterIterator::class)]
class LogRecordFilterIteratorTest extends TestCase
{
    public function testGetIteratorShouldFilterLevel(): void
    {
        $levels         = ['debug', 'info'];
        $debugRecord    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);

        $iterator = new LogRecordFilterIterator($recordIterator, $levels, null);
        static::assertSame([$debugRecord, $infoRecord], iterator_to_array($iterator));
    }

    public function testGetIteratorShouldFilterChannel(): void
    {
        $channels       = ['request', 'event'];
        $debugRecord    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);

        $iterator = new LogRecordFilterIterator($recordIterator, null, $channels);
        static::assertSame([$debugRecord, $warningRecord], array_values(iterator_to_array($iterator)));
    }

    public function testGetIteratorShouldNotFilter(): void
    {
        $requestRecord  = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $appRecord      = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $eventRecord    = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$requestRecord, $appRecord, $eventRecord]);

        $iterator = new LogRecordFilterIterator($recordIterator, null, null);
        static::assertSame([$requestRecord, $appRecord, $eventRecord], iterator_to_array($iterator));
    }
}
