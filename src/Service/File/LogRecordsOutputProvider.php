<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use Exception;
use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Iterator\LimitIterator;
use FD\LogViewer\Iterator\MultiLogRecordIterator;
use FD\LogViewer\Service\PerformanceService;

class LogRecordsOutputProvider
{
    public function __construct(
        private readonly LogFileParserProvider $logParserProvider,
        private readonly PerformanceService $performanceService,
    ) {
    }

    /**
     * @param array<string, LogFile> $files
     *
     * @throws Exception
     */
    public function provideForFiles(array $files, LogQueryDto $logQuery): LogRecordsOutput
    {
        $iterators = [];

        foreach ($files as $file) {
            $config      = $file->folder->collection->config;
            $iterators[] = $this->logParserProvider->get($config->type)->getLogIndex($config, $file, $logQuery)->getIterator();
        }

        $recordIterator   = new MultiLogRecordIterator($iterators, $logQuery->direction);
        $recordIterator   = new LimitIterator($recordIterator, $logQuery->perPage);
        $logIndexIterator = new LogIndexIterator($recordIterator, null);

        return new LogRecordsOutput($logIndexIterator, $this->performanceService->getPerformanceStats());
    }

    public function provide(LogFile $file, LogQueryDto $logQuery): LogRecordsOutput
    {
        $config   = $file->folder->collection->config;
        $logIndex = $this->logParserProvider->get($config->type)->getLogIndex($config, $file, $logQuery);

        return new LogRecordsOutput($logIndex, $this->performanceService->getPerformanceStats());
    }
}
