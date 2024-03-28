<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Iterator\LimitIterator;
use FD\LogViewer\Iterator\LogLineParserIterator;
use FD\LogViewer\Iterator\LogRecordFilterIterator;
use FD\LogViewer\Iterator\LogRecordIterator;
use FD\LogViewer\Iterator\MaxRuntimeIterator;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use Psr\Clock\ClockInterface;
use SplFileInfo;

class LogParser
{
    private const MAX_RUNTIME_IN_SECONDS = 10;

    public function __construct(
        private readonly ClockInterface $clock,
        private readonly LogRecordMatcher $logRecordMatcher,
        private readonly StreamReaderFactory $streamReaderFactory
    ) {
    }

    public function parse(SplFileInfo $file, LogLineParserInterface $lineParser, LogQueryDto $logQuery): LogRecordCollection
    {
        // create iterators
        $streamReader = $this->streamReaderFactory->createForFile($file, $logQuery->direction, $logQuery->offset);
        $lineIterator = new LogLineParserIterator($streamReader, $lineParser, $logQuery->direction);
        $iterator     = new MaxRuntimeIterator($this->clock, $lineIterator, self::MAX_RUNTIME_IN_SECONDS, false);
        $iterator     = new LogRecordIterator($iterator, $lineParser);
        if ($logQuery->query !== null || $logQuery->levels !== null || $logQuery->channels !== null) {
            $iterator = new LogRecordFilterIterator($this->logRecordMatcher, $iterator, $logQuery->query, $logQuery->levels, $logQuery->channels);
        }
        $iterator = new LimitIterator($iterator, $logQuery->perPage);

        return new LogRecordCollection(
            $iterator,
            function () use ($logQuery, $lineIterator) {
                // create paginator
                $hasOffset = (int)$logQuery->offset > 0;
                if ($lineIterator->isEOF() === false || $hasOffset) {
                    return new Paginator($logQuery->direction, $hasOffset, $lineIterator->isEOF() === false, $lineIterator->getPosition());
                }

                return null;
            }
        );
    }
}
