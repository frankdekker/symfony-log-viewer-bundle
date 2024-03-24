<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use Closure;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogIndexIterator implements IteratorAggregate
{
    /**
     * @param Traversable<int, LogRecord>  $lines
     * @param (Closure(): ?Paginator)|null $paginatorCallback
     */
    public function __construct(private readonly Traversable $lines, private readonly ?Closure $paginatorCallback = null)
    {
    }

    /**
     * @return LogRecord[]
     */
    public function getLines(): array
    {
        return iterator_to_array($this->lines);
    }

    public function getIterator(): Traversable
    {
        return $this->lines;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginatorCallback === null ? null : ($this->paginatorCallback)();
    }
}
