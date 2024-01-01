<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service;

use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use FD\SymfonyLogViewerBundle\Service\VersionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(PerformanceService::class)]
class PerformanceServiceTest extends TestCase
{
    private VersionService&MockObject $versionService;
    private RequestStack $requestStack;
    private PerformanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        ClockMock::register(self::class);
        ClockMock::register(PerformanceService::class);
        ClockMock::withClockMock(true);
        $this->requestStack   = new RequestStack();
        $this->versionService = $this->createMock(VersionService::class);
        $this->service        = new PerformanceService($this->requestStack, $this->versionService);
    }

    public function testGetPerformanceStats(): void
    {
        $request = new Request(server: ['REQUEST_TIME_FLOAT' => microtime(true) - 300]);
        $this->requestStack->push($request);

        $this->versionService->expects(self::once())->method('getVersion')->willReturn('1.2.3');

        $stats = $this->service->getPerformanceStats($request);
        $json  = $stats->jsonSerialize();
        static::assertNotEmpty($json['memoryUsage']);
        static::assertSame('300000ms', $json['requestTime']);
        static::assertSame('1.2.3', $json['version']);
    }
}
