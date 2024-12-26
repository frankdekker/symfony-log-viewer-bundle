<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Request\SearchQuery;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordFilterIterator implements IteratorAggregate
{
    /**
     * @param iterable<int, LogRecord> $iterator
     */
    public function __construct(private readonly LogRecordMatcher $matcher, private readonly iterable $iterator, private readonly SearchQuery $query)
    {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $key => $record) {
            if ($this->matcher->matches($record, $this->query) === false) {
                continue;
            }

            yield $key => $record;
        }
    }
}
