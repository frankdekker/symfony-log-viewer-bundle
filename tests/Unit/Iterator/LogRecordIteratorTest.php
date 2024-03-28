<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Iterator;

use ArrayIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Iterator\LogRecordIterator;
use FD\LogViewer\Service\File\LogLineParserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordIterator::class)]
class LogRecordIteratorTest extends TestCase
{
    private LogLineParserInterface&MockObject $lineParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lineParser = $this->createMock(LogLineParserInterface::class);
    }

    public function testGetIteratorShouldYieldErrorFromParser(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->lineParser->expects(self::once())->method('parse')->with('message')->willReturn(null);

        $recordIterator = new LogRecordIterator($iterator, $this->lineParser);

        $expectedRecord = new LogRecord('78e731027d8fd50ed642340b7c9a63b3', 0, 'error', 'parse', 'message', [], []);
        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }

    public function testGetIterator(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->lineParser->expects(self::once())
            ->method('parse')
            ->with('message')
            ->willReturn(
                [
                    'date'     => date('Y-m-d H:i:s', 111111),
                    'severity' => 'debug',
                    'channel'  => 'request',
                    'message'  => 'message',
                    'context'  => [],
                    'extra'    => [],
                ]
            );

        $recordIterator = new LogRecordIterator($iterator, $this->lineParser);
        $expectedRecord = new LogRecord('2d51458c3b1f974fae79ef1ce3d7e919', 111111, 'debug', 'request', 'message', [], []);

        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }
}
