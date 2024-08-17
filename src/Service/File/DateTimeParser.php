<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use DateTime;

class DateTimeParser
{
    public function __construct(private readonly ?string $format)
    {
    }

    public function parse(string $date): ?int
    {
        if ($this->format === null) {
            $timestamp = strtotime($date);

            return $timestamp === false ? null : $timestamp;
        }

        $dateTime = DateTime::createFromFormat($this->format, $date);

        return $dateTime === false ? null : $dateTime->getTimestamp();
    }
}
