<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use IteratorAggregate;
use Traversable;

/**
 * @template K of int|string
 * @template V
 * @implements IteratorAggregate<K, V>
 */
class MaxRuntimeIterator implements IteratorAggregate
{
    /**
     * @param Traversable<K, V> $iterator
     */
    public function __construct(private readonly Traversable $iterator, private readonly int $maxRuntimeInSeconds, private bool $throw = true)
    {
    }

    /**
     * @return Traversable<K, V>
     * @throws MaxRuntimeException
     */
    public function getIterator(): Traversable
    {
        $startTime = microtime(true);
        foreach ($this->iterator as $key => $value) {
            yield $key => $value;

            if (microtime(true) - $startTime > $this->maxRuntimeInSeconds) {
                if ($this->throw) {
                    throw new MaxRuntimeException();
                }
                break;
            }
        }
    }
}
