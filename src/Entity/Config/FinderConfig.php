<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class FinderConfig
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(
        public readonly string $inDirectories,
        public readonly ?string $fileName,
        public readonly ?int $depth,
        public readonly bool $ignoreUnreadableDirs,
        public readonly bool $followLinks
    ) {
    }
}
