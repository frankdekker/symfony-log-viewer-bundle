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

    public function testGetIteratorShouldFilterOnExpression(): void
    {
        $debugRecord    = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $infoRecord     = new LogRecord('id', 222222, 'info', 'app', 'message', [], []);
        $warningRecord  = new LogRecord('id', 333333, 'warning', 'event', 'message', [], []);
        $recordIterator = new ArrayIterator([$debugRecord, $infoRecord, $warningRecord]);
        $expression     = new Expression([]);

        $this->recordMatcher->expects(self::exactly(3))->method('matches')->with()->willReturn(true, false, false);

        $iterator = new LogRecordFilterIterator($this->recordMatcher, $recordIterator, $expression);
        static::assertSame([$debugRecord], array_values(iterator_to_array($iterator)));
    }
}
