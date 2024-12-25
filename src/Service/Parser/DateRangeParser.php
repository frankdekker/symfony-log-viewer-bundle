<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class DateRangeParser implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Format: <date>~<date>
     * Date format:
     * - now
     * - 15i (15 minutes ago)
     * - 2024-12-25+21:15:00
     * @return array{?DateTimeImmutable, ?DateTimeImmutable}
     * @throws DateMalformedStringException
     */
    public function parseDateRange(string $range, DateTimeZone $timeZone): array
    {
        if (preg_match('/^(.*)~(.*)$/', $range, $matches) === 1) {
            return [$this->parseDate($matches[1], $timeZone), $this->parseDate($matches[2], $timeZone)];
        }

        if ($range !== '') {
            $this->logger?->warning('Invalid date range: ' . $range);
        }

        return [null, null];
    }

    /**
     * @throws DateMalformedStringException
     */
    private function parseDate(string $date, DateTimeZone $timeZone): DateTimeImmutable
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
