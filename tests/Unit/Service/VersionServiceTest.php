<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service;

use FD\SymfonyLogViewerBundle\Service\VersionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VersionService::class)]
class VersionServiceTest extends TestCase
{
    public function testGetVersion(): void
    {
        static::assertSame('dev-master', (new VersionService())->getVersion());
    }
}
