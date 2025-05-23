<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

use DateTimeImmutable;

/**
 * @deprecated will be removed in v3.0
 */
class DateAfterTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(public readonly DateTimeImmutable $date)
    {
    }
}
