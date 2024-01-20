<?php
declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use IteratorAggregate;
use Psr\Clock\ClockInterface;
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
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly Traversable $iterator,
        private readonly int $maxRuntimeInSeconds,
        private bool $throw = true
    ) {
    }

    /**
     * @return Traversable<K, V>
     * @throws MaxRuntimeException
     */
    public function getIterator(): Traversable
    {
        $startTime = $this->clock->now()->getTimestamp();
        foreach ($this->iterator as $key => $value) {
            yield $key => $value;

            if ($this->clock->now()->getTimestamp() - $startTime > $this->maxRuntimeInSeconds) {
                if ($this->throw) {
                    throw new MaxRuntimeException();
                }
                break;
            }
        }
    }
}
