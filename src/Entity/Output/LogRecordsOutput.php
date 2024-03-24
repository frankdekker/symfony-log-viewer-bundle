<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use FD\LogViewer\Entity\Index\LogIndexIterator;
use FD\LogViewer\Entity\Index\PerformanceStats;
use JsonSerializable;

class LogRecordsOutput implements JsonSerializable
{
    public function __construct(private readonly LogIndexIterator $logIndex, private readonly PerformanceStats $performance)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'logs'        => $this->logIndex->getRecords(),
            'paginator'   => $this->logIndex->getPaginator(),
            'performance' => $this->performance
        ];
    }
}
