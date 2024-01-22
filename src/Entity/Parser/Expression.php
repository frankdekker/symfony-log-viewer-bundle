<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

class Expression
{
    /**
     * @param TermInterface[] $terms
     */
    public function __construct(public readonly array $terms)
    {
    }
}
