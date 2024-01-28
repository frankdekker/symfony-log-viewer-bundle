<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Nginx;

use FD\LogViewer\Service\File\LogLineParserInterface;

class NginxAccessLineParser implements LogLineParserInterface
{
    /** @noinspection RequiredAttributes */
    public const LOG_LINE_PATTERN =
        '/^(?P<ip>\S+) ' .
        '(?P<identity>\S+) ' .
        '(?P<remote_user>\S+) ' .
        '\[(?P<date>[^\]]+)\] ' .
        '"(?P<method>\S+) (?P<path>\S+) (?P<http_version>\S+)" ' .
        '(?P<status_code>\S+) ' .
        '(?P<content_length>\S+) ' .
        '"(?P<referrer>[^"]*)" ' .
        '"(?P<user_agent>[^"]*)"/';

    private readonly string $logLinePattern;

    public function __construct(?string $logLinePattern)
    {
        $this->logLinePattern = $logLinePattern ?? self::LOG_LINE_PATTERN;
    }

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
        if (preg_match($this->logLinePattern, $message, $matches) !== 1) {
            return null;
        }

        $filter  = ['date', 'method', 'path'];
        $context = array_filter(
            $matches,
            static fn($value, $key) => trim($value) !== '' && is_int($key) === false && in_array($key, $filter, true) === false,
            ARRAY_FILTER_USE_BOTH
        );

        return [
            'date'     => $matches['date'],
            'severity' => $matches['status_code'],
            'channel'  => '',
            'message'  => sprintf('%s %s', $matches['method'] ?? '', $matches['path'] ?? ''),
            'context'  => $context,
            'extra'    => '',
        ];
    }
}
