<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Output\DirectionEnum;

class LogRecordDateComparator
{
    public function __construct(private readonly DirectionEnum $direction)
    {
    }

    public function compare(LogRecord $left, LogRecord $right): int
    {
        return $this->direction === DirectionEnum::Asc ? $left->date <=> $right->date : $right->date <=> $left->date;
    }
}
