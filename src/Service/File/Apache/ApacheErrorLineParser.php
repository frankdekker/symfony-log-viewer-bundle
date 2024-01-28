<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File\Apache;

use FD\LogViewer\Service\File\LogLineParserInterface;

class ApacheErrorLineParser implements LogLineParserInterface
{
    public const LOG_LINE_PATTERN =
        '/^\[(?<date>.*?)\] ' .
        '\[(?:(?<module>.*?):)?(?<severity>.*?)\] ' .
        '\[pid\s(?<pid>\d*)\]' .
        '(?: (?<error_status>[^\]]*?))?' .
        '(?: \[client (?<ip>.*):(?<port>\d+)\]) ' .
        '(?<message>.*?)' .
        '(?:, referer: (?<referer>\S*?))?$/';

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

        $filter  = ['date', 'severity', 'message'];
        $context = array_filter(
            $matches,
            static fn($value, $key) => trim($value) !== '' && is_int($key) === false && in_array($key, $filter, true) === false,
            ARRAY_FILTER_USE_BOTH
        );

        return [
            'date'     => $matches['date'],
            'severity' => $matches['severity'],
            'channel'  => '',
            'message'  => $matches['message'],
            'context'  => $context,
            'extra'    => '',
        ];
    }
}
