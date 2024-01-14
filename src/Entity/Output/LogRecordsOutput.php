<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\Index\PerformanceStats;
use FD\LogViewer\Entity\Request\LogQueryDto;
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
                'selected' => $this->logQuery->levels ?? array_keys($this->levels)
            ],
            'channels'    => [
                'choices'  => $this->channels,
                'selected' => $this->logQuery->channels ?? array_keys($this->channels)
            ],
            'logs'        => $this->logIndex->getLines(),
            'paginator'   => $this->logIndex->getPaginator(),
            'performance' => $this->performance
        ];
    }
}
