<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class Expression
{
    /**
     * @codeCoverageIgnore Simple DTO
     * @param TermInterface[] $terms
     */
    public function __construct(public readonly array $terms)
    {
    }
}
