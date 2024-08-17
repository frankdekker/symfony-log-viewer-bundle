<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Iterator\LogRecordIterator;
use FD\LogViewer\Service\File\DateTimeParser;
use FD\LogViewer\Service\File\LogLineParserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordIterator::class)]
class LogRecordIteratorTest extends TestCase
{
    private DateTimeParser&MockObject $dateTimeParser;
    private LogLineParserInterface&MockObject $lineParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dateTimeParser = $this->createMock(DateTimeParser::class);
        $this->lineParser     = $this->createMock(LogLineParserInterface::class);
    }

    public function testGetIteratorShouldYieldErrorFromParser(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->lineParser->expects(self::once())->method('parse')->with('message')->willReturn(null);

        $recordIterator = new LogRecordIterator($iterator, $this->dateTimeParser, $this->lineParser);
        $expectedRecord = new LogRecord('78e731027d8fd50ed642340b7c9a63b3', 0, 'error', 'parse', 'message', [], []);
        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }

    public function testGetIteratorWithInvalidDate(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->dateTimeParser->expects(self::once())->method('parse')->with('2009-02-13 23:31:30')->willReturn(null);
        $this->lineParser->expects(self::once())
            ->method('parse')
            ->with('message')
            ->willReturn(
                [
                    'date'     => '2009-02-13 23:31:30',
                    'severity' => 'debug',
                    'channel'  => 'request',
                    'message'  => 'message',
                    'context'  => [],
                    'extra'    => [],
                ]
            );

        $recordIterator = new LogRecordIterator($iterator, $this->dateTimeParser, $this->lineParser);
        $expectedRecord = new LogRecord('78e731027d8fd50ed642340b7c9a63b3', 0, 'error', 'bad-date', 'message', [], []);

        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }

    public function testGetIterator(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->dateTimeParser->expects(self::once())->method('parse')->with('2009-02-13 23:31:30')->willReturn(1234567890);
        $this->lineParser->expects(self::once())
            ->method('parse')
            ->with('message')
            ->willReturn(
                [
                    'date'     => '2009-02-13 23:31:30',
                    'severity' => 'debug',
                    'channel'  => 'request',
                    'message'  => 'message',
                    'context'  => [],
                    'extra'    => [],
                ]
            );

        $recordIterator = new LogRecordIterator($iterator, $this->dateTimeParser, $this->lineParser);
        $expectedRecord = new LogRecord('ef088a39731e4d450d8692fdcc811415', 1234567890, 'debug', 'request', 'message', [], []);

        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }
}
