<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateTimeImmutable;
use Throwable;

class DateParser
{
    /**
     * @throws InvalidDateTimeException
     */
    public function toDateTimeImmutable(string $date): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($date);
        } catch (Throwable $exception) {
            throw new InvalidDateTimeException('Invalid date' . $date, 0, $exception);
        }
    }
}
