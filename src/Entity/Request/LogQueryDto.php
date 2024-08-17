<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Request;

use DateTimeZone;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Output\DirectionEnum;

class LogQueryDto
{
    /**
     * @codeCoverageIgnore - Simple DTO
     *
     * @param string[] $fileIdentifiers
     */
    public function __construct(
        public readonly array $fileIdentifiers,
        public readonly DateTimeZone $timeZone,
        public readonly ?int $offset = null,
        public readonly ?Expression $query = null,
        public readonly DirectionEnum $direction = DirectionEnum::Desc,
        public readonly int $perPage = 100
    ) {
    }
}
