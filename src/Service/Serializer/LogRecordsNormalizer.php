<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Serializer;

use DateTime;
use DateTimeZone;
use FD\LogViewer\Entity\Index\LogRecord;
use Psr\Log\LogLevel;

class LogRecordsNormalizer
{
    // bootstrap 5 text colors
    private const LEVEL_CLASSES = [
        LogLevel::DEBUG     => 'text-info',
        LogLevel::INFO      => 'text-info',
        LogLevel::NOTICE    => 'text-info',
        LogLevel::WARNING   => 'text-warning',
        LogLevel::ERROR     => 'text-danger',
        LogLevel::ALERT     => 'text-danger',
        LogLevel::CRITICAL  => 'text-danger',
        LogLevel::EMERGENCY => 'text-danger',
    ];

    /**
     * @param LogRecord[] $records
     *
     * @return array{
     *     datetime: string,
     *     level_name: string,
     *     level_class: string,
     *     channel: string,
     *     text: string,
     *     context: string|array<array-key, mixed>,
     *     extra: string|array<array-key, mixed>
     * }[]
     */
    public function normalize(array $records, DateTimeZone $timeZone): array
    {
        $date   = new DateTime(timezone: $timeZone);
        $result = [];
        foreach ($records as $record) {
            $result[] = [
                'datetime'    => $date->setTimestamp($record->date)->format('Y-m-d H:i:s'),
                'level_name'  => ucfirst($record->severity),
                'level_class' => self::LEVEL_CLASSES[$record->severity] ?? 'text-info',
                'channel'     => $record->channel,
                'text'        => $record->message,
                'context'     => $record->context,
                'extra'       => $record->extra,
            ];
        }

        return $result;
    }
}
