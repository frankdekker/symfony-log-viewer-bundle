<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateTimeZone;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Reader\String\StringReader;

/**
 * BNF:
 * <expression> ::= <term> | <term> <expression>
 * <term> ::= <date-term> | <exclude-term> | <severity-term> | <channel-term> <string> | <context-term> | <extra-term>
 * <date-term> ::= before:<string> | af
 * <severity-term> ::= severity:<string>
 * <channel-term> ::= channel:<string>
 * <exclude-term> ::= exclude:<string>
 * <context-term> ::= context:<string> | context:<key>=<string>
 * <extra-term> ::= extra:<string> | extra:<key>=<string>
 * <string> ::= "<characters without unescaped double quote>" | '<characters without unescaped quote>' | <characters-without-space>
 */
class ExpressionParser
{
    public function __construct(private readonly TermParser $termParser)
    {
    }

    /**
     * @throws InvalidDateTimeException
     */
    public function parse(StringReader $string, DateTimeZone $timeZone): Expression
    {
        $terms = [];

        while ($string->eol() === false) {
            $string->skipWhitespace();
            $terms[] = $this->termParser->parse($string, $timeZone);
            $string->skipWhitespace();
        }

        return new Expression($terms);
    }
}
