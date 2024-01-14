<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Index;

use FD\LogViewer\Entity\Index\PerformanceStats;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PerformanceStats::class)]
class PerformanceStatsTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $stats = new PerformanceStats('1.23GB', '1.23s', '1.0.1');

        static::assertSame(
            [
                'memoryUsage' => '1.23GB',
                'requestTime' => '1.23s',
                'version'     => '1.0.1',
            ],
            $stats->jsonSerialize()
        );
    }
}
