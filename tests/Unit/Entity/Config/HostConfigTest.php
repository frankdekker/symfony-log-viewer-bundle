<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity\Config;

use FD\LogViewer\Entity\Config\HostConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HostConfig::class)]
class HostConfigTest extends TestCase
{
    public function testIsLocal(): void
    {
        $hostConfig = new HostConfig('key', 'name', null);
        static::assertTrue($hostConfig->isLocal());

        $hostConfig = new HostConfig('key', 'name', 'url');
        static::assertFalse($hostConfig->isLocal());
    }
}
