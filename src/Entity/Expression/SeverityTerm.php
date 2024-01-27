<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class SeverityTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param string[] $severities
     */
    public function __construct(public readonly array $severities)
    {
    }
}
