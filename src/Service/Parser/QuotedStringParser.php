<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;

class QuotedStringParser
{
    public function parse(StringReader $string, string $quote, string $escapeChar): string
    {
        // skip the opening quote
        $string->next();

        $result  = '';
        $escaped = false;
        for (; $string->eol() === false; $string->next()) {
            $char = $string->char();

            // skip the escape character
            if ($char === $escapeChar && $escaped === false) {
                $escaped = true;
                continue;
            }

            if ($escaped === false && $char === $quote) {
                break;
            }

            if ($escaped) {
                $result  .= $escapeChar;
                $escaped = false;
            }

            $result .= $char;
        }

        // skip the closing quote
        $string->next();

        return $result;
    }
}
