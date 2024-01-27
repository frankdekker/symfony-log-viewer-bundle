<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Output;

use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Index\PerformanceStats;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsOutput::class)]
class LogRecordsOutputTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $levels    = ['level1' => 'level1', 'level2' => 'level2'];
        $channels  = ['channel1' => 'channel1', 'channel2' => 'channel2'];
        $paginator = new Paginator(DirectionEnum::Asc, true, true, 123);
        $record    = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $logIndex  = new LogIndex();
        $logIndex->addLine($record);
        $logIndex->setPaginator($paginator);
        $performance = $this->createMock(PerformanceStats::class);

        $logRecordsOutput = new LogRecordsOutput($levels, $channels, $logIndex, $performance);

        static::assertSame(
            [
                'levels'      => $levels,
                'channels'    => $channels,
                'logs'        => [$record],
                'paginator'   => $paginator,
                'performance' => $performance
            ],
            $logRecordsOutput->jsonSerialize()
        );
    }
}
