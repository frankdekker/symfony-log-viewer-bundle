<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use FD\LogViewer\Entity\Config\HostConfig;
use Traversable;

class HostProvider
{
    /**
     * @param Traversable<HostConfig> $hosts
     */
    public function __construct(private readonly Traversable $hosts)
    {
    }

    public function getHostByKey(string $key): ?HostConfig
    {
        /** @var HostConfig $host */
        foreach ($this->hosts as $host) {
            if ($host->key === $key) {
                return $host;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public function getHosts(): array
    {
        $isLocalOnly = true;
        $hosts       = [];

        /** @var HostConfig $host */
        foreach ($this->hosts as $host) {
            if ($host->host !== null) {
                $isLocalOnly = false;
            }

            $hosts[$host->key] = $host->name;
        }

        return $isLocalOnly ? [] : $hosts;
    }
}
