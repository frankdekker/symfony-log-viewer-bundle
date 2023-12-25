<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\StreamReader;

use Generator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, string>
 */
abstract class AbstractStreamReader implements IteratorAggregate
{
    public const DIRECTION_FORWARD = 1;
    public const DIRECTION_REVERSE = 2;

    /**
     * @return Generator<int, string>
     */
    abstract public function getIterator(): Traversable;

    abstract public function isEOF(): bool;

    /**
     * @param resource          $handle
     * @param self::DIRECTION_* $direction
     */
    public function __construct(protected $handle, private readonly int $direction, private readonly bool $autoClose = true)
    {
    }

    public function __destruct()
    {
        if ($this->autoClose) {
            fclose($this->handle);
        }
    }

    public function getDirection(): int
    {
        return $this->direction;
    }

    abstract public function getPosition(): int;
}
