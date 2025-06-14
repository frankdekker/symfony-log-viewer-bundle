<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Output;

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
        $paginator   = new Paginator(DirectionEnum::Asc, true, true, 123);
        $record      = new LogRecord('id', 'record', 111111, 'debug', 'request', 'message', [], []);
        $performance = $this->createMock(PerformanceStats::class);

        $logRecordsOutput = new LogRecordsOutput([$record], $paginator, $performance);

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
