<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

use DateTimeImmutable;

class DateBeforeTerm implements TermInterface
{
    public function __construct(public readonly DateTimeImmutable $date)
    {
    }
}
