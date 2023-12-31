<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\Paginator;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Iterator\LimitIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogLineParserIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordFilterIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordIterator;
use FD\SymfonyLogViewerBundle\Iterator\MaxRuntimeIterator;
use FD\SymfonyLogViewerBundle\StreamReader\StreamReaderFactory;
use SplFileInfo;

class LogParser
{
    private const MAX_RUNTIME_IN_SECONDS = 10;

    public function __construct(private readonly StreamReaderFactory $streamReaderFactory)
    {
    }

    public function parse(SplFileInfo $file, LogLineParserInterface $lineParser, LogQueryDto $logQuery): LogIndex
    {
        // create iterators
        $streamReader = $this->streamReaderFactory->createForFile($file, $logQuery->direction, $logQuery->offset);
        $lineIterator = new LogLineParserIterator($streamReader, $lineParser, $logQuery->direction);
        $iterator     = new MaxRuntimeIterator($lineIterator, self::MAX_RUNTIME_IN_SECONDS, false);
        $iterator     = new LogRecordIterator($iterator, $lineParser, $logQuery->query);
        if ($logQuery->levels !== null || $logQuery->channels !== null) {
            $iterator = new LogRecordFilterIterator($iterator, $logQuery->levels, $logQuery->channels);
        }
        $iterator = new LimitIterator($iterator, $logQuery->perPage);

        // loop over all lines and create index
        $index = new LogIndex();
        foreach ($iterator as $logLine) {
            $index->addLine($logLine);
        }

        // stream reader didn't reach the end
        if ($lineIterator->isEOF() === false) {
            $index->setPaginator(new Paginator($logQuery->direction, $logQuery->offset === null, true, $lineIterator->getPosition()));
        }

        return $index;
    }
}
