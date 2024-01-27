<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;

class StringParser
{
    public function __construct(private readonly QuotedStringParser $quotedStringParser, private readonly WordParser $wordParser)
    {
    }

    public function parse(StringReader $string): string
    {
        if (in_array($string->get(), ['"', "'"], true)) {
            return $this->quotedStringParser->parse($string, $string->get(), '\\');
        }

        return $this->wordParser->parse($string);
    }
}
