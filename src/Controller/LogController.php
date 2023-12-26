<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Service\LogFileService;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\LogParser;
use FD\SymfonyLogViewerBundle\Service\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\Monolog\MonologLineParser;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use Monolog\Logger;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogController extends AbstractController
{
    /**
     * @param iterable<int, Logger> $loggerLocator
     */
    public function __construct(
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly LogFileService $fileService,
        private readonly LogParser $logParser,
        private readonly LogFolderOutputFactory $folderOutputFactory,
        private readonly PerformanceService $performanceService,
        private readonly iterable $loggerLocator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $channels = [];
        foreach ($this->loggerLocator as $logger) {
            $channels[$logger->getName()] = $logger->getName();
        }
        ksort($channels);

        $logLevels = [
            'emergency' => 'Emergency',
            'alert'     => 'Alert',
            'critical'  => 'Critical',
            'error'     => 'Error',
            'warning'   => 'Warning',
            'notice'    => 'Notice',
            'info'      => 'Info',
            'debug'     => 'Debug'
        ];

        $logQuery = $this->queryDtoFactory->create($request);

        $file = $this->fileService->findFileByIdentifier($logQuery->fileIdentifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $logQuery->fileIdentifier));
        }

        $logIndex = $this->logParser->parse(new SplFileInfo($file->path), new MonologLineParser(), $logQuery);

        return $this->json(
            [
                'file'        => $this->folderOutputFactory->createFromFile($file),
                'levels'      => [
                    'choices'  => $logLevels,
                    'selected' => count($logQuery->levels) === 0 ? array_keys($logLevels) : $logQuery->levels
                ],
                'channels'    => [
                    'choices'  => $channels,
                    'selected' => count($logQuery->channels) === 0 ? array_keys($channels) : $logQuery->channels
                ],
                'logs'        => $logIndex->getLines(),
                'paginator'   => $logIndex->getPaginator(),
                'performance' => $this->performanceService->getPerformanceStats($request)
            ]
        );
    }
}
