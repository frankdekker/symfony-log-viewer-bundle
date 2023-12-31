<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Iterator;

use ArrayIterator;
use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordIterator;
use FD\SymfonyLogViewerBundle\Service\File\LogLineParserInterface;
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

    public function testGetIteratorShouldSkipIfSearchQueryDoesNotMatch(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->lineParser->expects(self::never())->method('parse')->with('message');

        $recordIterator = new LogRecordIterator($iterator, $this->lineParser, 'foo');

        static::assertEquals([], iterator_to_array($recordIterator));
    }

    public function testGetIteratorShouldSkipNullFromParser(): void
    {
        $iterator = new ArrayIterator(['message']);

        $this->lineParser->expects(self::once())->method('parse')->with('message')->willReturn(null);

        $recordIterator = new LogRecordIterator($iterator, $this->lineParser, '');

        static::assertEquals([], iterator_to_array($recordIterator));
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

        $recordIterator = new LogRecordIterator($iterator, $this->lineParser, '');
        $expectedRecord = new LogRecord(111111, 'debug', 'request', 'message', [], []);

        static::assertEquals([$expectedRecord], iterator_to_array($recordIterator));
    }
}
