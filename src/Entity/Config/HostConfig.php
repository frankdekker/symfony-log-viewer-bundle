<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class HostConfig
{
    /**
     * @codeCoverageIgnore Simple DTO
     */
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly ?string $host,
        public readonly ?HostAuthenticationConfig $authentication = null,
    ) {
    }
}
