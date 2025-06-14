<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use DigitalRevolution\AccessorPairConstraint\AccessorPairAsserter;
use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecord::class)]
class LogRecordTest extends TestCase
{
    use AccessorPairAsserter;

    public function testContextLine(): void
    {
        $record = new LogRecord('identifier', 'record', 123, 'warn', 'app', 'message', [], []);
        static::assertFalse($record->isContextLine());

        $record->setContextLine(true);
        static::assertTrue($record->isContextLine());
    }

    public function testGetIdentifier(): void
    {
        $record = new LogRecord('id', 'record', 111, 'debug', 'request', 'message', [], []);

        static::assertSame('id', $record->getIdentifier());
    }
}
