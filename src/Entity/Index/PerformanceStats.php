<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use JsonSerializable;

class PerformanceStats implements JsonSerializable
{
    public function __construct(
        private readonly string $memoryUsage,
        private readonly string $requestTime,
        private readonly string $version
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'memoryUsage' => $this->memoryUsage,
            'requestTime' => $this->requestTime,
            'version'     => $this->version
        ];
    }
}
