<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Request;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;

class LogQueryDto
{
    /**
     * @param string[] $levels
     * @param string[] $channels
     */
    public function __construct(
        public readonly string $fileIdentifier,
        public readonly ?int $offset,
        public readonly string $query,
        public readonly DirectionEnum $direction,
        public readonly array $levels,
        public readonly array $channels,
        public readonly int $perPage
    ) {
    }
}
