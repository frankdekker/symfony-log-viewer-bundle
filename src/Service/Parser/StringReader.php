<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use LogicException;

class StringReader
{
    private readonly int $length;
    private int $position = 0;

    /** @var int[] */
    private array $marks = [];

    public function __construct(private readonly string $string)
    {
        $this->length = strlen($this->string);
    }

    public function get(): string
    {
        return $this->string[$this->position];
    }

    public function peek(int $length = 1): string
    {
        return substr($this->string, $this->position, $length);
    }

    /**
     * @param string[] $chars
     */
    public function skip(array $chars): self
    {
        while ($this->eol() === false && in_array($this->get(), $chars, true)) {
            $this->next();
        }

        return $this;
    }

    public function skipWhitespace(): self
    {
        return $this->skip([" ", "\t", "\n", "\r"]);
    }

    /**
     * Move the cursor to the next character
     */
    public function next(): self
    {
        $this->position++;

        return $this;
    }

    /**
     * True if the end of line is reached
     */
    public function eol(): bool
    {
        return $this->position >= $this->length;
    }

    /**
     * Mark the current position to optionally restore later
     */
    public function mark(): self
    {
        $this->marks[] = $this->position;

        return $this;
    }

    /**
     * Unmark the current position
     */
    public function unmark(): self
    {
        array_pop($this->marks);

        return $this;
    }

    /**
     * Restore the position to the previously marked position
     */
    public function restore(): self
    {
        $position = array_pop($this->marks);
        if ($position === false) {
            throw new LogicException('No mark to restore');
        }

        $this->position = (int)$position;

        return $this;
    }
}
