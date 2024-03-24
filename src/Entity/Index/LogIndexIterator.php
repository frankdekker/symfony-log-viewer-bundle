<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use ArrayIterator;
use Closure;
use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogIndexIterator implements IteratorAggregate
{
    /** @var LogRecord[]|null */
    private ?array $records = null;

    /**
     * @param Traversable<int, LogRecord>  $iterator
     * @param (Closure(): ?Paginator)|null $paginatorCallback
     */
    public function __construct(private readonly Traversable $iterator, private readonly ?Closure $paginatorCallback = null)
    {
    }

    /**
     * @return LogRecord[]
     */
    public function getRecords(): array
    {
        if ($this->records !== null) {
            return $this->records;
        }

        $records = [];
        foreach ($this->iterator as $record) {
            $records[] = $record;
        }

        return $this->records = $records;
    }

    public function getIterator(): Iterator
    {
        if ($this->records !== null) {
            return new ArrayIterator($this->records);
        }

        $records = [];
        foreach ($this->iterator as $record) {
            $records[] = $record;
            yield $record;
        }
        $this->records = $records;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginatorCallback === null ? null : ($this->paginatorCallback)();
    }
}
