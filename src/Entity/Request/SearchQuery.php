<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity\Request;

use DateTimeImmutable;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Expression\LineAfterTerm;
use FD\LogViewer\Entity\Expression\LineBeforeTerm;

class SearchQuery
{
    public function __construct(
        public readonly ?Expression $query = null,
        public readonly ?DateTimeImmutable $afterDate = null,
        public readonly ?DateTimeImmutable $beforeDate = null
    ) {
    }

    public function getLinesBefore(): int
    {
        return $this->query?->getTerm(LineBeforeTerm::class)->lines ?? 0;
    }

    public function getLinesAfter(): int
    {
        return $this->query?->getTerm(LineAfterTerm::class)->lines ?? 0;
    }
}
