<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Iterator;

use ArrayIterator;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Iterator\LogLineParserIterator;
use FD\SymfonyLogViewerBundle\Service\File\LogLineParserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogLineParserIterator::class)]
class LogLineParserIteratorTest extends TestCase
{
    private LogLineParserInterface&MockObject $lineParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lineParser = $this->createMock(LogLineParserInterface::class);
        $this->service    = new ($this->lineParser)();
    }

    public function testGetIteratorAscending(): void
    {
        $lines        = ['line1', 'line2', 'line3', 'line4', 'line5'];
        $lineIterator = new ArrayIterator($lines);

        $this->lineParser->expects(self::exactly(5))
            ->method('matches')
            ->willReturn(
                LogLineParserInterface::MATCH_SKIP,
                LogLineParserInterface::MATCH_START,
                LogLineParserInterface::MATCH_APPEND,
                LogLineParserInterface::MATCH_APPEND,
                LogLineParserInterface::MATCH_START
            );

        $iterator = new LogLineParserIterator($lineIterator, $this->lineParser, DirectionEnum::Asc);
        static::assertSame(['line2line3line4', 'line5'], iterator_to_array($iterator));
    }

    public function testGetIteratorDescending(): void
    {
        $lines        = ['line5', 'line4', 'line3', 'line2', 'line1'];
        $lineIterator = new ArrayIterator($lines);

        $this->lineParser->expects(self::exactly(5))
            ->method('matches')
            ->willReturn(
                LogLineParserInterface::MATCH_SKIP,
                LogLineParserInterface::MATCH_START,
                LogLineParserInterface::MATCH_APPEND,
                LogLineParserInterface::MATCH_APPEND,
                LogLineParserInterface::MATCH_START
            );

        $iterator = new LogLineParserIterator($lineIterator, $this->lineParser, DirectionEnum::Desc);
        static::assertSame(['line4', 'line1line2line3'], iterator_to_array($iterator));
    }
}
