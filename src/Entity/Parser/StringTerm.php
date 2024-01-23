<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

class StringTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(public readonly string $string)
    {
    }
}
