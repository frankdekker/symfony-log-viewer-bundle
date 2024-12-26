<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

use DateTimeImmutable;

class DateAfterTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(public readonly DateTimeImmutable $date)
    {
    }
}
