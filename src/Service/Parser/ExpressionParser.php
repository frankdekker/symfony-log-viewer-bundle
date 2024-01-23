<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use Exception;
use FD\LogViewer\Entity\Parser\Expression;
use FD\LogViewer\Reader\String\StringReader;

/**
 * BNF:
 * <expression> ::= <term> | <term> <expression>
 * <term> ::= <date-term> <string>
 * <date-term> ::= before:<string> | after:<string>
 * <string> ::= "<characters without unescaped double quote>" | '<characters without unescaped quote>' | <characters-without-space>
 */
class ExpressionParser
{
    public function __construct(private readonly TermParser $termParser)
    {
    }

    /**
     * @throws Exception
     */
    public function parse(StringReader $string): Expression
    {
        $terms = [];

        while ($string->eol() === false) {
            $string->skipWhitespace();
            $terms[] = $this->termParser->parse($string);
            $string->skipWhitespace();
        }

        return new Expression($terms);
    }
}
