<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologFileParser;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LogRecordsController extends AbstractController
{
    public function __construct(
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly MonologFileParser $logParser,
        private readonly PerformanceService $performanceService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $levels      = $this->logParser->getLevels();
        $channels    = $this->logParser->getChannels();
        $logQuery    = $this->queryDtoFactory->create($request, $levels, $channels);
        $logIndex    = $this->logParser->getLogIndex($logQuery);
        $performance = $this->performanceService->getPerformanceStats($request);

        $output = new LogRecordsOutput($levels, $channels, $logQuery, $logIndex, $performance);

        return new JsonResponse($output);
    }
}
