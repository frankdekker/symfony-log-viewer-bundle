<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Service\File\LogRecordDateComparator;
use Iterator;
use IteratorAggregate;
use Traversable;

use function array_fill;
use function array_keys;
use function count;

/**
 * The iterator that will:
 * - Take the first entry from each iterator and add it to the buffer
 * - Determine which entry is the best based on LogRecordDateComparator
 * - yield the entry
 * - remove the entry from the buffer and replenish the buffer.
 * @implements IteratorAggregate<int, LogRecord>
 */
class MultiLogRecordIterator implements IteratorAggregate
{
    /** @var array<int, LogRecord|null> */
    private array $buffer;

    /**
     * @param array<int, Iterator<int, LogRecord>> $iterators
     */
    public function __construct(private readonly array $iterators, private readonly LogRecordDateComparator $comparator)
    {
        // initialize buffer
        $this->buffer = array_fill(0, count($this->iterators), null);
    }

    public function getIterator(): Traversable
    {
        foreach (array_keys($this->buffer) as $index) {
            $this->updateBufferAt($index);
        }

        while (count($this->buffer) > 0) {
            yield $this->takeEntryFromBuffer();
        }
    }

    private function takeEntryFromBuffer(): LogRecord
    {
        $selected = null;
        $index    = null;

        foreach ($this->buffer as $i => $record) {
            // updateBufferAt will ensure no records are null
            assert($record !== null);

            if ($selected === null || $this->comparator->compare($selected, $record) === 1) {
                $selected = $record;
                $index    = $i;
            }
        }

        if ($index !== null) {
            $this->buffer[$index] = null;
            $this->updateBufferAt($index);
        }

        assert($selected !== null);

        return $selected;
    }

    private function updateBufferAt(int $index): void
    {
        $iterator = $this->iterators[$index];
        assert($this->buffer[$index] === null);

        if ($iterator->valid() === false) {
            unset($this->buffer[$index]);

            return;
        }

        $this->buffer[$index] = $iterator->current();
        $iterator->next();
    }
}
