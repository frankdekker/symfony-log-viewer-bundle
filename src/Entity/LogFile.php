<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

class LogFile
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(
        public readonly string $identifier,
        public readonly string $path,
        public readonly string $relativePath,
        public readonly int $size,
        public readonly int $createTimestamp,
        public readonly int $updateTimestamp,
        public readonly LogFolder $folder
    ) {
    }
}
