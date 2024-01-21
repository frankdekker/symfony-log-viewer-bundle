<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class FinderConfig
{
    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param int|string|string[]|null $depth
     */
    public function __construct(
        public readonly string $inDirectories,
        public readonly ?string $fileName,
        public readonly int|string|array|null $depth,
        public readonly bool $ignoreUnreadableDirs,
        public readonly bool $followLinks
    ) {
    }
}
