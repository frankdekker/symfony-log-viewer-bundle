<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;

class StringParser
{
    public function __construct(private readonly QuotedStringParser $quotedStringParser, private readonly WordParser $wordParser)
    {
    }

    /**
     * @param string[] $stopAt
     */
    public function parse(StringReader $string, array $stopAt = []): string
    {
        if (in_array($string->char(), ['"', "'"], true)) {
            return $this->quotedStringParser->parse($string, $string->char(), '\\');
        }

        return $this->wordParser->parse($string, $stopAt);
    }
}
