<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class LineAfterTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(public readonly int $lines)
    {
    }
}
