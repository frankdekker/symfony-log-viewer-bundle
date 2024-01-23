<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

class WordsTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param WordTerm[] $words
     */
    public function __construct(public readonly array $words)
    {
    }
}
