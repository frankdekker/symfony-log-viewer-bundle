<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use ArrayIterator;
use Closure;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, LogRecord>
 */
class LogIndexIterator implements IteratorAggregate
{
    /** @var LogRecord[]|null */
    private ?array $lines = null;

    /**
     * @param Traversable<int, LogRecord>  $lineIterator
     * @param (Closure(): ?Paginator)|null $paginatorCallback
     */
    public function __construct(private readonly Traversable $lineIterator, private readonly ?Closure $paginatorCallback = null)
    {
    }

    /**
     * @return LogRecord[]
     */
    public function getLines(): array
    {
        if ($this->lines !== null) {
            return $this->lines;
        }

        $lines = [];
        foreach ($this->lineIterator as $line) {
            $lines[] = $line;
        }

        return $this->lines = $lines;
    }

    public function getIterator(): Traversable
    {
        if ($this->lines !== null) {
            return new ArrayIterator($this->lines);
        }

        $lines = [];
        foreach ($this->lineIterator as $line) {
            $lines[] = $line;
            yield $line;
        }
        $this->lines = $lines;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginatorCallback === null ? null : ($this->paginatorCallback)();
    }
}
