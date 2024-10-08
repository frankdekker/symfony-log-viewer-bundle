<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecord::class)]
class LogRecordTest extends TestCase
{
    public function testGetIdentifier(): void
    {
        $record = new LogRecord('id', 111, 'debug', 'request', 'message', [], []);

        static::assertSame('id', $record->getIdentifier());
    }
}
