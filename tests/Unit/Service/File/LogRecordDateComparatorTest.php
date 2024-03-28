<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Service\File\LogRecordDateComparator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordDateComparator::class)]
class LogRecordDateComparatorTest extends TestCase
{
    public function testCompareAscending(): void
    {
        $recordA = new LogRecord('id', 333333, 'debug', 'request', 'message', [], []);
        $recordB = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $recordC = new LogRecord('id', 222222, 'debug', 'request', 'message', [], []);
        $records = [$recordA, $recordB, $recordC];

        $comparator = new LogRecordDateComparator(DirectionEnum::Asc);
        uasort($records, [$comparator, 'compare']);

        static::assertSame([$recordB, $recordC, $recordA], array_values($records));
    }

    public function testCompareDescending(): void
    {
        $recordA = new LogRecord('id', 333333, 'debug', 'request', 'message', [], []);
        $recordB = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $recordC = new LogRecord('id', 222222, 'debug', 'request', 'message', [], []);
        $records = [$recordA, $recordB, $recordC];

        $comparator = new LogRecordDateComparator(DirectionEnum::Desc);
        uasort($records, [$comparator, 'compare']);

        static::assertSame([$recordA, $recordC, $recordB], array_values($records));
    }
}
