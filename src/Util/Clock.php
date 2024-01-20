<?php
declare(strict_types=1);

namespace FD\LogViewer\Util;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class Clock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
