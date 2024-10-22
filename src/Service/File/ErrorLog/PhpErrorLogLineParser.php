<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\File\ErrorLog;

use FD\LogViewer\Service\File\LogLineParserInterface;

class PhpErrorLogLineParser implements LogLineParserInterface
{
    public const LOG_LINE_PATTERN = '/^\[(?<date>.*?)\] (?<message>.*?)$/';

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

        return [
            'date'     => $matches['date'],
            'severity' => $matches['severity'] ?? 'error',
            'channel'  => $matches['channel'] ?? 'error_log',
            'message'  => $matches['message'],
            'context'  => $matches['context'] ?? '',
            'extra'    => $matches['extra'] ?? '',
        ];
    }
}
