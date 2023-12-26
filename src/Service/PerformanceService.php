<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Index\PerformanceStats;
use FD\SymfonyLogViewerBundle\Util\Utils;
use Symfony\Component\HttpFoundation\Request;

class PerformanceService
{
    public function __construct(private readonly VersionService $versionService)
    {
    }

    public function getPerformanceStats(Request $request): PerformanceStats
    {
        $memoryUsage = Utils::bytesForHumans(memory_get_usage());

        $startTime = $request->server->get('REQUEST_TIME_FLOAT');
        assert(is_float($startTime));
        $requestTime = round((microtime(true) - $startTime) * 1000) . 'ms';

        $version = $this->versionService->getVersion();

        return new PerformanceStats($memoryUsage, $requestTime, $version);
    }
}
