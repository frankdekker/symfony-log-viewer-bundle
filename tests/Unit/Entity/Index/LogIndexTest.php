<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogIndex::class)]
class LogIndexTest extends TestCase
{
    use AccessorPairAsserter;

    public function testAccessorPairs(): void
    {
        static::assertAccessorPairs(LogIndex::class);
    }

    public function testAddGetLine(): void
    {
        $record = new LogRecord(111111, 'debug', 'request', 'message', [], []);
        $index  = new LogIndex();
        $index->addLine($record);
        static::assertSame([$record], $index->getLines());
    }
}
