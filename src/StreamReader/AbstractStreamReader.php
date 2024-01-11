<?php
declare(strict_types=1);

namespace FD\LogViewer\StreamReader;

use Generator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, string>
 */
abstract class AbstractStreamReader implements IteratorAggregate
{
    /**
     * @return Generator<int, string>
     */
    abstract public function getIterator(): Traversable;

    abstract public function isEOF(): bool;

    /**
     * @param resource $handle
     */
    public function __construct(protected $handle, private readonly bool $autoClose = true)
    {
    }

    public function __destruct()
    {
        if ($this->autoClose) {
            fclose($this->handle);
        }
    }

    abstract public function getPosition(): int;

    protected function ftell(): int
    {
        $position = ftell($this->handle);
        assert($position !== false);

        return $position;
    }
}
