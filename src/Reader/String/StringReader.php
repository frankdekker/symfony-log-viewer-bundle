<?php
declare(strict_types=1);

namespace FD\LogViewer\Reader\String;

class StringReader
{
    private const WHITESPACES = [" ", "\t", "\n", "\r"];

    private readonly int $length;
    private int $position = 0;

    public function __construct(private readonly string $string)
    {
        $this->length = strlen($this->string);
    }

    public function get(): string
    {
        return $this->string[$this->position];
    }

    /**
     * Returns the next characters without moving the cursor
     */
    public function peek(int $length): string
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
        return $this->skip(self::WHITESPACES);
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
}
