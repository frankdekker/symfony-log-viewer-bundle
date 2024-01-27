<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\Index\PerformanceStats;
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
            'levels'      => $this->levels,
            'channels'    => $this->channels,
            'logs'        => $this->logIndex->getLines(),
            'paginator'   => $this->logIndex->getPaginator(),
            'performance' => $this->performance
        ];
    }
}
