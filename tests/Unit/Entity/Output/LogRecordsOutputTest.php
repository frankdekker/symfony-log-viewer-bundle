<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Entity\Output;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;
use FD\SymfonyLogViewerBundle\Entity\Index\Paginator;
use FD\SymfonyLogViewerBundle\Entity\Index\PerformanceStats;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsOutput::class)]
class LogRecordsOutputTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $levels    = ['level1' => 'level1', 'level2' => 'level2'];
        $channels  = ['channel1' => 'channel1', 'channel2' => 'channel2'];
        $logQuery  = new LogQueryDto('file', 123, 'search', DirectionEnum::Asc, ['foo' => 'foo'], ['bar' => 'bar'], 50);
        $paginator = new Paginator(DirectionEnum::Asc, true, true, 123);
        $record    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $logIndex  = new LogIndex();
        $logIndex->addLine($record);
        $logIndex->setPaginator($paginator);
        $performance = $this->createMock(PerformanceStats::class);

        $logRecordsOutput = new LogRecordsOutput($levels, $channels, $logQuery, $logIndex, $performance);

        static::assertSame(
            [
                'levels'      => [
                    'choices'  => $levels,
                    'selected' => ['foo']
                ],
                'channels'    => [
                    'choices'  => $channels,
                    'selected' => ['bar']
                ],
                'logs'        => [$record],
                'paginator'   => $paginator,
                'performance' => $performance
            ],
            $logRecordsOutput->jsonSerialize()
        );
    }
}
