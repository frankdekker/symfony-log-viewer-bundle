<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\StreamReader;

use Generator;

class ForwardStreamReader extends AbstractStreamReader
{
    private int $position;

    /**
     * @param resource $handle
     */
    public function __construct($handle, private readonly int $offset, bool $autoClose = true)
    {
        parent::__construct($handle, $autoClose);
        $this->position = $this->offset;
    }

    /**
     * @return Generator<string>
     */
    public function getIterator(): Generator
    {
        fseek($this->handle, $this->offset);

        while (($line = fgets($this->handle)) !== false) {
            $this->position += strlen($line);
            yield $line;
        }
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isEOF(): bool
    {
        return feof($this->handle);
    }
}
