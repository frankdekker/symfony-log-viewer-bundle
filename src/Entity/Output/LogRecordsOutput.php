<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Output;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\PerformanceStats;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use JsonSerializable;

class LogRecordsOutput implements JsonSerializable
{
    /**
     * @param array<string, string> $levels
     * @param array<string, string> $channels
     */
    public function __construct(
        private readonly array $levels,
        private readonly array $channels,
        private readonly LogQueryDto $logQuery,
        private readonly LogIndex $logIndex,
        private readonly PerformanceStats $performance
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'levels'      => [
                'choices'  => $this->levels,
                'selected' => $this->logQuery->levels === null ? array_keys($this->levels) : $this->logQuery->levels
            ],
            'channels'    => [
                'choices'  => $this->channels,
                'selected' => $this->logQuery->channels === null ? array_keys($this->channels) : $this->logQuery->channels
            ],
            'logs'        => $this->logIndex->getLines(),
            'paginator'   => $this->logIndex->getPaginator(),
            'performance' => $this->performance
        ];
    }
}
