<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use FD\LogViewer\Entity\Index\Paginator;
use FD\LogViewer\Entity\Index\PerformanceStats;
use JsonSerializable;

class LogRecordsOutput implements JsonSerializable
{
    /**
     * @param array<array-key, mixed> $records
     */
    public function __construct(
        private readonly array $records,
        private readonly ?Paginator $paginator,
        private readonly ?PerformanceStats $performance
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'logs'        => $this->records,
            'paginator'   => $this->paginator,
            'performance' => $this->performance
        ];
    }
}
