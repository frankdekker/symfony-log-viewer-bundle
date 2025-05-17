<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity\Request;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\Expression;

class SearchQuery
{
    /**
     * @codeCoverageIgnore - Simple DTO
     */
    public function __construct(
        public readonly ?Expression $query = null,
        public readonly ?DateTimeImmutable $afterDate = null,
        public readonly ?DateTimeImmutable $beforeDate = null
    ) {
    }
}
