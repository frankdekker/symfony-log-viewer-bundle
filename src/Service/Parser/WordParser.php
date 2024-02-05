<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;

class WordParser
{
    public const WHITESPACE = [' ' => true, "\t" => true, "\n" => true, "\r" => true];

    /**
     * @param string[] $stopAt
     */
    public function parse(StringReader $string, array $stopAt = []): string
    {
        $result = '';
        for (; $string->eol() === false; $string->next()) {
            $char = $string->char();
            if (isset(self::WHITESPACE[$char]) || in_array($char, $stopAt, true)) {
                break;
            }
            $result .= $char;
        }

        return $result;
    }
}
