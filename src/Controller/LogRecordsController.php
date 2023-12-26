<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologFileParser;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRecordsController extends AbstractController
{
    public function __construct(
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly MonologFileParser $logParser,
        private readonly PerformanceService $performanceService,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $levels   = $this->logParser->getLevels();
        $channels = $this->logParser->getChannels();
        $logQuery = $this->queryDtoFactory->create($request, $levels, $channels);
        $logIndex = $this->logParser->getLogIndex($logQuery);

        return $this->json(
            [
                'levels'      => [
                    'choices'  => $levels,
                    'selected' => $logQuery->levels === null ? array_keys($levels) : $logQuery->levels
                ],
                'channels'    => [
                    'choices'  => $channels,
                    'selected' => $logQuery->channels === null ? array_keys($channels) : $logQuery->channels
                ],
                'logs'        => $logIndex->getLines(),
                'paginator'   => $logIndex->getPaginator(),
                'performance' => $this->performanceService->getPerformanceStats($request)
            ]
        );
    }
}
