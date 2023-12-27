<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File\Monolog;

use FD\SymfonyLogViewerBundle\Service\File\LogLineParserInterface;
use JsonException;

class MonologLineParser implements LogLineParserInterface
{
    /* [YYYY-MM-DD*] channel.level: */
    private const START_OF_MESSAGE_PATTERN = '/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/';

    /* [date] channel.level: message {context} {extra} */
    private const LOG_LINE_PATTERN =
        '/^\[(?P<date>[^\]]+)\]\s+' .
        '(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+' .
        '(?P<message>.*?)\s+' .
        '(?P<context>[[{].*?[\]}])\s+' .
        '(?P<extra>[[{].*?[\]}])\s+$/s';

    /**
     * @inheritDoc
     */
    public function matches(string $line): int
    {
        return preg_match(self::START_OF_MESSAGE_PATTERN, $line) === 1 ? self::MATCH_START : self::MATCH_APPEND;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $message): ?array
    {
        if (preg_match(self::LOG_LINE_PATTERN, $message, $matches) !== 1) {
            return null;
        }

        return [
            'date'     => $matches['date'],
            'severity' => $matches['severity'],
            'channel'  => $matches['channel'],
            'message'  => $matches['message'],
            'context'  => self::toJsonOrString($matches['context']),
            'extra'    => self::toJsonOrString($matches['extra']),
        ];
    }

    /**
     * @return string|array<int|string, mixed>
     */
    private function toJsonOrString(string $data): string|array
    {
        try {
            $json = json_decode($data, true, flags: JSON_THROW_ON_ERROR);

            return is_array($json) ? $json : $data;
        } catch (JsonException) {
            return $data;
        }
    }
}
