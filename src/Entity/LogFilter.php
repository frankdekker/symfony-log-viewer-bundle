<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

class LogFilter
{
    /**
     * @param string[] $levels
     * @param string[] $channels
     */
    public function __construct(public readonly array $levels, public readonly array $channels, public readonly string $searchQuery)
    {
    }

    public function hasFilter(): bool
    {
        return count($this->levels) > 0 || count($this->channels) > 0;
    }
}
