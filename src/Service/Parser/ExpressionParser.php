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
 *            | <line-before-term> | <line-after-term>
 * <date-term> ::= before:<string> | b:<string> | after:<string> | a:<string>
 * <severity-term> ::= severity:<string>
 * <channel-term> ::= channel:<string>
 * <exclude-term> ::= exclude:<string>
 * <context-term> ::= context:<string> | context:<key>=<string>
 * <extra-term> ::= extra:<string> | extra:<key>=<string>
 * <line-before-term> ::= line-before:<number> | lb:<number>
 * <line-after-term> ::= line-after:<number> | la:<number>
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
