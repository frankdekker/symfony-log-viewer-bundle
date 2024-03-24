<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Request;

use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Output\DirectionEnum;

class LogQueryDto
{
    /**
     * @codeCoverageIgnore - Simple DTO
     *
     * @param string[]      $fileIdentifiers
     * @param string[]|null $levels
     * @param string[]|null $channels
     */
    public function __construct(
        public readonly array $fileIdentifiers,
        public readonly ?int $offset = null,
        public readonly ?Expression $query = null,
        public readonly DirectionEnum $direction = DirectionEnum::Desc,
        public readonly ?array $levels = null,
        public readonly ?array $channels = null,
        public readonly int $perPage = 100
    ) {
    }
}
