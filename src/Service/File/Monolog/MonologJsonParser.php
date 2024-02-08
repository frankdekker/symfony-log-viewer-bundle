<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Monolog;

use FD\LogViewer\Service\File\LogLineParserInterface;
use JsonException;

class MonologJsonParser implements LogLineParserInterface
{
    /**
     * @inheritDoc
     */
    public function matches(string $line): int
    {
        return self::MATCH_START;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $message): ?array
    {
        try {
            $json = json_decode($message, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (is_array($json) === false) {
            return null;
        }

        return [
            'date'     => $json['datetime'],
            'severity' => $json['level_name'],
            'channel'  => $json['channel'],
            'message'  => $json['message'],
            'context'  => $json['context'] ?? [],
            'extra'    => $json['extra'] ?? [],
        ];
    }
}
