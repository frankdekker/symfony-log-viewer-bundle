<?php

declare(strict_types=1);

namespace FD\LogViewer\Iterator;

use FD\LogViewer\Entity\IdentifierAwareInterface;
use IteratorAggregate;
use Traversable;

/**
 * @template K of int|string
 * @template V of IdentifierAwareInterface
 * @implements IteratorAggregate<int, V>
 */
class DeduplicationIterator implements IteratorAggregate
{
    /** @var array<string, true> */
    private array $lookup = [];

    /**
     * @param iterable<K, V> $iterator
     */
    public function __construct(private readonly iterable $iterator)
    {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $entry) {
            $identifier = $entry->getIdentifier();
            if (isset($this->lookup[$identifier])) {
                continue;
            }

            $this->lookup[$identifier] = true;
            yield $entry;
        }
    }
}
