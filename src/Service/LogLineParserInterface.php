<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

interface LogLineParserInterface
{
    public const MATCH_START  = 1;
    public const MATCH_APPEND = 2;
    public const MATCH_SKIP   = 3;

    /**
     * @return self::MATCH_* returns
     *      - MATCH_START when the line is the start of a log entry.
     *      - MATCH_APPEND when the line should be appended to the previous log entry.
     *      - MATCH_SKIP when the line should be skipped.
     */
    public function matches(string $line): int;

    /**
     * @return array{
     *     date: string,
     *     severity: string,
     *     channel: string,
     *     message: string,
     *     context: string|array<int|string, mixed>,
     *     extra: string|array<int|string, mixed>
     * }|null Returns null if the line could not be parsed.
     */
    public function parse(string $message): ?array;
}
