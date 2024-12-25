<?php

declare(strict_types=1);

namespace FD\LogViewer\Util;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

class DateUtil
{
    /**
     * @throws Exception
     */
    public static function tryParseTimezone(?string $timezone, string $default): DateTimeZone
    {
        if ($timezone === null) {
            return new DateTimeZone($default);
        }

        try {
            return new DateTimeZone($timezone);
        } catch (Exception) {
            return new DateTimeZone($default);
        }
    }

    /**
     * Format: <date>~<date>
     * Date format:
     * - now
     * - 15i (15 minutes ago)
     * - 2024-12-25+21:15:00
     * @return array{?DateTimeImmutable, ?DateTimeImmutable}
     * @throws DateMalformedStringException
     */
    public static function parseDateRange(string $range, DateTimeZone $timeZone): array
    {
        if (preg_match('/^(.*)~(.*)$/', $range, $matches) === 1) {
            return [self::parseDate($matches[1], $timeZone), self::parseDate($matches[2], $timeZone)];
        }

        return [null, null];
    }

    /**
     * @throws DateMalformedStringException
     */
    private static function parseDate(string $date, DateTimeZone $timeZone): DateTimeImmutable
    {
        if ($date === 'now') {
            return new DateTimeImmutable('now', $timeZone);
        }

        if (preg_match('/(\d+)([sihdwmy])/', $date, $matches) === 1) {
            $interval = match ($matches[2]) {
                's' => "seconds",
                'i' => "minutes",
                'h' => "hours",
                'd' => "days",
                'w' => "weeks",
                'm' => "months",
                'y' => "years"
            };

            return new DateTimeImmutable("-" . $matches[1] . " " . $interval, $timeZone);
        }

        return new DateTimeImmutable($date, $timeZone);
    }
}
