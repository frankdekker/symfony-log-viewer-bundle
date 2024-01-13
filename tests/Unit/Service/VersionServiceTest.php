<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service;

use FD\LogViewer\Service\VersionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VersionService::class)]
class VersionServiceTest extends TestCase
{
    public function testGetVersion(): void
    {
        static::assertStringStartsWith('dev-', (new VersionService())->getVersion());
    }
}
