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
    /** @var array<string, int>|null */
    private readonly ?array $levels;
    /** @var array<string, int>|null */
    private readonly ?array $channels;

    /**
     * @param Traversable<int, LogRecord> $iterator
     * @param string[]|null               $levels
     * @param string[]|null               $channels
     */
    public function __construct(private readonly Traversable $iterator, ?array $levels, ?array $channels)
    {
        $this->levels   = $levels === null ? null : array_flip($levels);
        $this->channels = $channels === null ? null : array_flip($channels);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $key => $record) {
            if ($this->levels !== null && isset($this->levels[$record->severity]) === false) {
                continue;
            }

            if ($this->channels !== null && isset($this->channels[$record->channel]) === false) {
                continue;
            }

            yield $key => $record;
        }
    }
}
