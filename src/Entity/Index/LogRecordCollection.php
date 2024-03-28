<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use Closure;
use Iterator;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogRecordCollection implements IteratorAggregate
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
            return yield from $this->records;
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
        if ($this->records === null) {
            throw new LogicException('Cannot get paginator before records are read');
        }

        return $this->paginatorCallback === null ? null : ($this->paginatorCallback)();
    }
}
