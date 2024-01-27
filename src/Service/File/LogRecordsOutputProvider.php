<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\PerformanceService;

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
            $logParser->getLogIndex($config, $file, $logQuery),
            $this->performanceService->getPerformanceStats()
        );
    }
}
