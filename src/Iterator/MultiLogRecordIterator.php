<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Output\DirectionEnum;
use IteratorAggregate;
use Traversable;

/**
 * An iterator that will:
 * - Take the first entry from each iterator and add it to the buffer
 * - Determine which entry is the newest or oldest (based or direction)
 * - yield the entry
 * - remove the entry from the buffer and replenish the buffer.
 * @implements IteratorAggregate<int, LogRecord>
 */
class MultiLogRecordIterator implements IteratorAggregate
{
    /** @var array<int, LogRecord|null> */
    private array $buffer;

    /**
     * @param array<int, Traversable<int, LogRecord>> $iterators
     */
    public function __construct(private readonly array $iterators, private readonly DirectionEnum $direction)
    {
        // initialize buffer
        $this->buffer = array_fill(0, count($this->iterators), null);
    }

    public function getIterator(): Traversable
    {
        while (count($this->buffer) > 0) {
            $buffer = $this->createSelectionFromBuffer();

            $record = $this->getEntryFromBuffer($buffer);
            if ($record === null) {
                break;
            }
            yield $record;
            $this->createSelectionFromBuffer();
        }
    }

    /**
     * @return LogRecord[];
     */
    private function createSelectionFromBuffer(): array
    {
        foreach ($this->iterators as $index => $iterator) {
            if ($this->buffer[$index] === null) {
                $record = next($iterator);
                if ($record instanceof LogRecord === false) {
                    unset($this->buffer[$index]);
                    continue;
                }
                $this->buffer[$index] = $record;
            }
        }

        /** @var array<int, LogRecord> */
        return $this->buffer;
    }

    /**
     * @param array<int, LogRecord> $buffer
     */
    private function getEntryFromBuffer(array $buffer): ?LogRecord
    {
        $selected = null;
        foreach ($buffer as $record) {
            if ($selected === null) {
                $selected = $record;
            } elseif ($this->direction === DirectionEnum::Asc && $selected->date < $record->date) {
                $selected = $record;
            } elseif ($this->direction === DirectionEnum::Desc && $selected->date > $record->date) {
                $selected = $record;
            }
        }

        return $selected;
    }
}
