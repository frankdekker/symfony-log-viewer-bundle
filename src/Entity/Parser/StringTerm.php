<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

class StringTerm implements TermInterface
{
    public function __construct(public readonly string $string)
    {
    }
}
