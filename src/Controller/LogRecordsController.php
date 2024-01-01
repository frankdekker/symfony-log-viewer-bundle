<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Service\File\LogFileParserProvider;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogRecordsController extends AbstractController
{
    public function __construct(
        private readonly LogFileService $fileService,
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly LogFileParserProvider $logParserProvider,
        private readonly PerformanceService $performanceService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $logQuery = $this->queryDtoFactory->create($request);
        $file     = $this->fileService->findFileByIdentifier($logQuery->fileIdentifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $logQuery->fileIdentifier));
        }

        $config      = $file->folder->collection->config;
        $logParser   = $this->logParserProvider->get($config->type);
        $levels      = $logParser->getLevels();
        $channels    = $logParser->getChannels();
        $logIndex    = $logParser->getLogIndex($config, $file, $logQuery);
        $performance = $this->performanceService->getPerformanceStats($request);

        return new JsonResponse(new LogRecordsOutput($levels, $channels, $logQuery, $logIndex, $performance));
    }
}
