<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Output;

use ArrayIterator;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Index\LogRecordCollection;
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
        $paginator        = new Paginator(DirectionEnum::Asc, true, true, 123);
        $record           = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $recordCollection = new LogRecordCollection(new ArrayIterator([$record]), fn() => $paginator);
        $performance      = $this->createMock(PerformanceStats::class);

        $logRecordsOutput = new LogRecordsOutput($recordCollection, $performance);

        static::assertSame(
            [
                'logs'        => [$record],
                'paginator'   => $paginator,
                'performance' => $performance
            ],
            $logRecordsOutput->jsonSerialize()
        );
    }
}
