<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\Paginator;
use FD\SymfonyLogViewerBundle\Entity\LogFilter;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Iterator\LimitIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogMessageIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordFilterIterator;
use FD\SymfonyLogViewerBundle\Iterator\LogRecordIterator;
use FD\SymfonyLogViewerBundle\Iterator\MaxRuntimeIterator;
use SplFileInfo;

class LogParser
{
    private const MAX_RUNTIME_IN_SECONDS = 10;

    public function __construct(private readonly StreamReaderFactory $streamReaderFactory)
    {
    }

    public function parse(
        SplFileInfo $file,
        LogLineParserInterface $lineParser,
        DirectionEnum $direction,
        int $perPage,
        ?int $offset,
        LogFilter $filter
    ): LogIndex {
        // create iterators
        $lineIterator = $this->streamReaderFactory->createForFile($file, $direction, $offset);
        $iterator     = new LogMessageIterator($lineIterator, $lineParser, $direction);
        $iterator     = new MaxRuntimeIterator($iterator, self::MAX_RUNTIME_IN_SECONDS, false);
        $iterator     = new LogRecordIterator($iterator, $lineParser, $filter->searchQuery);
        if ($filter->hasFilter()) {
            $iterator = new LogRecordFilterIterator($iterator, $filter);
        }
        $iterator = new LimitIterator($iterator, $perPage);

        // loop over all lines and create index
        $index = new LogIndex($file->getInode(), 0, $file->getSize());
        foreach ($iterator as $logLine) {
            $index->addLine($logLine);
        }

        // stream reader didn't reach the end
        if ($lineIterator->isEOF() === false) {
            $index->setPaginator(new Paginator($direction, $offset === null, true, $lineIterator->getPosition()));
        }

        return $index;
    }
}
