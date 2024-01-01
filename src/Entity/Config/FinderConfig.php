<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Config;

class FinderConfig
{
    public function __construct(
        public readonly string $inDirectories,
        public readonly ?string $fileName,
        public readonly bool $ignoreUnreadableDirs,
        public readonly bool $followLinks
    ) {
    }
}
