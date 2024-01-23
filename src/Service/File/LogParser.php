<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Iterator\LimitIterator;
use FD\LogViewer\Iterator\LogLineParserIterator;
use FD\LogViewer\Iterator\LogRecordFilterIterator;
use FD\LogViewer\Iterator\LogRecordIterator;
use FD\LogViewer\Iterator\MaxRuntimeIterator;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use Psr\Clock\ClockInterface;
use SplFileInfo;

class LogParser
{
    private const MAX_RUNTIME_IN_SECONDS = 10;

    public function __construct(private readonly ClockInterface $clock, private readonly StreamReaderFactory $streamReaderFactory)
    {
    }

    public function parse(SplFileInfo $file, LogLineParserInterface $lineParser, LogQueryDto $logQuery): LogIndex
    {
        // create iterators
        $streamReader = $this->streamReaderFactory->createForFile($file, $logQuery->direction, $logQuery->offset);
        $lineIterator = new LogLineParserIterator($streamReader, $lineParser, $logQuery->direction);
        $iterator     = new MaxRuntimeIterator($this->clock, $lineIterator, self::MAX_RUNTIME_IN_SECONDS, false);
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

        // create paginator
        $hasOffset = (int)$logQuery->offset > 0;
        if ($lineIterator->isEOF() === false || $hasOffset) {
            $index->setPaginator(new Paginator($logQuery->direction, $hasOffset, $lineIterator->isEOF() === false, $lineIterator->getPosition()));
        }

        return $index;
    }
}
