<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Iterator\LogRecordFilterIterator;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordFilterIterator::class)]
class LogRecordFilterIteratorTest extends TestCase
{
    private LogRecordMatcher&MockObject $recordMatcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recordMatcher = $this->createMock(LogRecordMatcher::class);
    }

    public function testGetIteratorShouldFilterLevel(): void
    {
        $levels         = ['debug', 'info'];
        $debugRecord    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);

        $iterator = new LogRecordFilterIterator($this->recordMatcher, $recordIterator, null, $levels, null);
        static::assertSame([$debugRecord, $infoRecord], iterator_to_array($iterator));
    }

    public function testGetIteratorShouldFilterChannel(): void
    {
        $channels       = ['request', 'event'];
        $debugRecord    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);

        $iterator = new LogRecordFilterIterator($this->recordMatcher, $recordIterator, null, null, $channels);
        static::assertSame([$debugRecord, $warningRecord], array_values(iterator_to_array($iterator)));
    }

    public function testGetIteratorShouldFilterOnExpression(): void
    {
        $debugRecord    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);
        $expression     = new Expression([]);

        $this->recordMatcher->expects(self::exactly(3))->method('matches')->with()->willReturn(true, false, false);

        $iterator = new LogRecordFilterIterator($this->recordMatcher, $recordIterator, $expression, null, null);
        static::assertSame([$debugRecord], array_values(iterator_to_array($iterator)));
    }

    public function testGetIteratorShouldNotFilter(): void
    {
        $requestRecord  = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $appRecord      = new LogRecord(222222, 'info', 'app', 'message', [], []);
        $eventRecord    = new LogRecord(333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$requestRecord, $appRecord, $eventRecord]);

        $iterator = new LogRecordFilterIterator($this->recordMatcher, $recordIterator, null, null, null);
        static::assertSame([$requestRecord, $appRecord, $eventRecord], iterator_to_array($iterator));
    }
}
