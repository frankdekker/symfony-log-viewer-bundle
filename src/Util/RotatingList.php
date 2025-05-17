<?php

declare(strict_types=1);

namespace FD\LogViewer\Util;

/**
 * @template T
 */
class RotatingList
{
    /** @var T[] */
    private array $items = [];

    public function __construct(private readonly int $maxSize)
    {
    }

    public function add(mixed $item): void
    {
        if ($this->maxSize === 0) {
            return;
        }

        $this->items[] = $item;

        if (count($this->items) > $this->maxSize) {
            array_shift($this->items);
        }
    }

    /**
     * @return T[]
     */
    public function getAll(): array
    {
        return $this->items;
    }

    public function clear(): void
    {
        $this->items = [];
    }
}
