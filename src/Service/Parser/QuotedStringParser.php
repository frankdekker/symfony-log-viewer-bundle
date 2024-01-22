<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

class QuotedStringParser
{
    public function parse(StringReader $string, string $quote, string $escapeChar): string
    {
        // skip the opening quote
        $string->next();

        $result       = '';
        $previousChar = null;
        for (; $string->eol() === false; $string->next()) {
            $char = $string->get();

            if ($char === $escapeChar) {
                if ($previousChar === null) {
                    $previousChar = $char;
                } elseif ($previousChar === $char) {
                    $previousChar = null;
                }
                continue;
            }

            if ($previousChar === null && $char === $quote) {
                break;
            }

            $result       .= $char;
            $previousChar = null;
        }

        return $result;
    }
}
