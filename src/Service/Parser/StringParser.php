<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

class StringParser
{
    public const WHITESPACE = [' ' => true, "\t" => true, "\n" => true, "\r" => true];

    public function __construct(private QuotedStringParser $quotedStringParser)
    {
    }

    public function parse(StringReader $string): string
    {
        $char = $string->peek();
        if ($char === '"' || $char === "'") {
            return $this->quotedStringParser->parse($string, $char, '\\');
        }

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
