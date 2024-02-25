<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class HostAuthenticationConfig
{
    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param array<int|string, mixed> $options
     */
    public function __construct(
        public readonly string $type,
        public readonly array $options = [],
    ) {
    }
}
