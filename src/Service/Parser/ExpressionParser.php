<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use Exception;
use FD\LogViewer\Entity\Parser\Expression;
use FD\LogViewer\Entity\Parser\WordsTerm;
use FD\LogViewer\Entity\Parser\WordTerm;
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
        $words = [];

        while ($string->eol() === false) {
            $string->skipWhitespace();
            $term = $this->termParser->parse($string);
            if ($term instanceof WordTerm) {
                $words[] = $term;
            } else {
                $terms[] = $term;
            }
            $string->skipWhitespace();
        }

        if (count($words) > 0) {
            $terms[] = new WordsTerm($words);
        }

        return new Expression($terms);
    }
}
