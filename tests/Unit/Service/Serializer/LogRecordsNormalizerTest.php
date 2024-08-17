<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Serializer;

use DateTimeZone;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\Serializer\LogRecordsNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsNormalizer::class)]
class LogRecordsNormalizerTest extends TestCase
{
    private LogRecordsNormalizer $normalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->normalizer = new LogRecordsNormalizer();
    }

    public function testNormalize(): void
    {
        $record   = new LogRecord('id', 1234567890, 'debug', 'request', 'message', ['context'], ['extra']);
        $timeZone = new DateTimeZone('America/New_York');

        $expected = [
            [
                'datetime'    => '2009-02-13 18:31:30',
                'level_name'  => 'Debug',
                'level_class' => 'text-info',
                'channel'     => 'request',
                'text'        => 'message',
                'context'     => ['context'],
                'extra'       => ['extra']
            ]
        ];
        $result   = $this->normalizer->normalize([$record], $timeZone);

        static::assertSame($expected, $result);
    }
}
