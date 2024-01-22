<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

use DateTimeImmutable;

class DateAfterTerm implements TermInterface
{
    public function __construct(public readonly DateTimeImmutable $date)
    {
    }
}
