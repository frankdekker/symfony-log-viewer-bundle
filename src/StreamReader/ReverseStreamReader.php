<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\StreamReader;

use Generator;

class ReverseStreamReader extends AbstractStreamReader
{
    private int $position;

    /**
     * @param resource $handle
     */
    public function __construct($handle, ?int $offset = null, private readonly int $bufferSize = 50000, bool $autoClose = true)
    {
        parent::__construct($handle, self::DIRECTION_REVERSE, $autoClose);
        if ($offset !== null) {
            fseek($handle, $offset);
        } else {
            fseek($this->handle, 0, SEEK_END);
        }
        $this->position = ftell($this->handle);
    }

    /**
     * @return Generator<string>
     */
    public function getIterator(): Generator
    {
        $this->position = $end = ftell($this->handle);
        assert($end !== false);

        while ($end > 0) {
            fseek($this->handle, max(0, $end - $this->bufferSize));
            // read away first (partial) line
            if (ftell($this->handle) !== 0) {
                fgets($this->handle);
            }
            // remember starting position
            $start = ftell($this->handle);

            // read all lines to the end of the buffer
            $lines = [];
            while (($line = fgets($this->handle)) !== false) {
                $lines[] = $line;
                if (ftell($this->handle) >= $end) {
                    break;
                }
            }

            // loop over lines in reverse order
            $len = count($lines);
            for ($j = $len; $j > 0; $j--) {
                yield $lines[$j - 1];
                $this->position -= strlen($lines[$j - 1]);
            }

            // move cursor
            $end = $start;
        }
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isEOF(): bool
    {
        return $this->getPosition() === 0;
    }
}
