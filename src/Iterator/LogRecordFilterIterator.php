<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator;

use FD\SymfonyLogViewerBundle\Entity\Index\LogRecord;
use FD\SymfonyLogViewerBundle\Entity\LogFilter;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordFilterIterator implements IteratorAggregate
{
    /** @var array<string, int> */
    private readonly array $levels;

    /** @var array<string, int> */
    private readonly array $channels;

    /**
     * @param Traversable<int, LogRecord> $iterator
     */
    public function __construct(private readonly Traversable $iterator, LogFilter $filter)
    {
        $this->levels   = array_flip($filter->levels);
        $this->channels = array_flip($filter->channels);
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
