<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;

class DateParser
{
    /**
     * @throws InvalidDateTimeException
     */
    public function toDateTimeImmutable(string $date, DateTimeZone $timeZone): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($date, $timeZone);
        } catch (Throwable $exception) {
            throw new InvalidDateTimeException('Invalid date ' . $date, 0, $exception);
        }
    }
}
