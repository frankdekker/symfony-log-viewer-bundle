<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class LogFilesConfig
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(
        public readonly string $logName,
        public readonly string $type,
        public readonly ?string $name,
        public readonly FinderConfig $finderConfig,
        public readonly bool $downloadable,
        public readonly bool $deletable,
        public readonly ?string $startOfLinePattern,
        public readonly ?string $logMessagePattern,
        public readonly ?string $dateFormat
    ) {
    }
}
