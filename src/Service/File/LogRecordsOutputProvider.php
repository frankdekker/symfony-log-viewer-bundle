<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;

class LogRecordsOutputProvider
{
    public function __construct(
        private readonly LogFileParserProvider $logParserProvider,
        private readonly PerformanceService $performanceService,
    ) {
    }

    public function provide(LogFilesConfig $config, LogFile $file, LogQueryDto $logQuery): LogRecordsOutput
    {
        $logParser = $this->logParserProvider->get($config->type);

        return new LogRecordsOutput(
            $logParser->getLevels(),
            $logParser->getChannels(),
            $logQuery,
            $logParser->getLogIndex($config, $file, $logQuery),
            $this->performanceService->getPerformanceStats()
        );
    }
}
