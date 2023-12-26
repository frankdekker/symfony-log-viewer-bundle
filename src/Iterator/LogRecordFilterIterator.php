<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator;

use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordFilterIterator implements IteratorAggregate
{
    /**
     * @param Traversable<int, LogRecord> $iterator
     * @param array<string, int>               $levels
     * @param array<string, int>               $channels
     */
    public function __construct(private readonly Traversable $iterator, private readonly array $levels, private readonly array $channels)
    {
    }

    public function getIterator(): Traversable
    {
        /** @var LogRecord $record */
        foreach ($this->iterator as $key => $record) {
            if (count($this->levels) > 0 && isset($this->levels[$record->severity]) === false) {
                continue;
            }

            if (count($this->channels) > 0 && isset($this->channels[$record->channel]) === false) {
                continue;
            }

            yield $key => $record;
        }
    }
}
