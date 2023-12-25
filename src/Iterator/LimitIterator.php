<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Iterator;

use IteratorAggregate;
use Traversable;

/**
 * @template K of int|string
 * @template V
 * @implements IteratorAggregate<K, V>
 */
class LimitIterator implements IteratorAggregate
{
    /**
     * @param Traversable<K, V> $iterator
     */
    public function __construct(private readonly Traversable $iterator, private readonly int $limit)
    {
    }

    public function getIterator(): Traversable
    {
        $count = 0;
        foreach ($this->iterator as $key => $value) {
            yield $key => $value;
            ++$count;
            if ($count >= $this->limit) {
                break;
            }
        }
    }
}
