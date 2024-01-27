<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Reader\String\StringReader;

class WordParser
{
    public const WHITESPACE = [' ' => true, "\t" => true, "\n" => true, "\r" => true];

    public function parse(StringReader $string): string
    {
        $result = '';
        for (; $string->eol() === false; $string->next()) {
            $char = $string->get();
            if (isset(self::WHITESPACE[$char])) {
                break;
            }
            $result .= $char;
        }

        return $result;
    }
}
