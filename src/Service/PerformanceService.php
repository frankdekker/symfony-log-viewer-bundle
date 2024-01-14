<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use FD\LogViewer\Entity\Index\PerformanceStats;
use FD\LogViewer\Util\Utils;
use Symfony\Component\HttpFoundation\RequestStack;

use function assert;
use function is_float;
use function memory_get_usage;
use function round;

class PerformanceService
{
    public function __construct(private readonly RequestStack $requestStack, private readonly VersionService $versionService)
    {
    }

    public function getPerformanceStats(): PerformanceStats
    {
        $memoryUsage = Utils::bytesForHumans(memory_get_usage());

        $startTime = $this->requestStack->getCurrentRequest()?->server?->get('REQUEST_TIME_FLOAT');
        assert(is_float($startTime));
        $requestTime = round((microtime(true) - $startTime) * 1000) . 'ms';

        $version = $this->versionService->getVersion();

        return new PerformanceStats($memoryUsage, $requestTime, $version);
    }
}
