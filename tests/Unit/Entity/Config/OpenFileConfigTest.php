<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Config;

use FD\LogViewer\Entity\Config\OpenFileConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenFileConfig::class)]
class OpenFileConfigTest extends TestCase
{
    #[TestWith(['*.log', '/var/log/app.log', true])]
    #[TestWith(['*.log', '/var/log/app.txt', false])]
    #[TestWith(['/var/log/*.log', '/var/log/app.log', true])]
    public function testMatches(string $pattern, string $filepath, bool $expected): void
    {
        $config = new OpenFileConfig($pattern, OpenFileConfig::ORDER_NEWEST);
        static::assertSame($expected, $config->matches($filepath));
    }
}
