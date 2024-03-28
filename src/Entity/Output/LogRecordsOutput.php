<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\PerformanceStats;
use JsonSerializable;

class LogRecordsOutput implements JsonSerializable
{
    public function __construct(private readonly LogRecordCollection $recordCollection, private readonly PerformanceStats $performance)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'logs'        => $this->recordCollection->getRecords(),
            'paginator'   => $this->recordCollection->getPaginator(),
            'performance' => $this->performance
        ];
    }
}
