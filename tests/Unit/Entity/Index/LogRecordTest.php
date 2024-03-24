<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use FD\LogViewer\Entity\Index\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecord::class)]
class LogRecordTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $record = new LogRecord('id', 946684800, 'debug', 'request', 'message', ['context' => 'context'], ['extra' => 'extra']);

        static::assertSame(
            [
                'datetime'    => '2000-01-01 00:00:00',
                'level_name'  => 'Debug',
                'level_class' => 'text-info',
                'channel'     => 'request',
                'text'        => 'message',
                'context'     => ['context' => 'context'],
                'extra'       => ['extra' => 'extra'],
            ],
            $record->jsonSerialize()
        );
    }
}
