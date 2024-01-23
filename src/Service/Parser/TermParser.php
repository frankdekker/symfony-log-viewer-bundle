<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateTimeImmutable;
use Exception;
use FD\LogViewer\Entity\Parser\DateAfterTerm;
use FD\LogViewer\Entity\Parser\DateBeforeTerm;
use FD\LogViewer\Entity\Parser\TermInterface;
use FD\LogViewer\Entity\Parser\WordTerm;
use FD\LogViewer\Reader\String\StringReader;

/**
 * BNF
 * <term> ::= <date-term> <string>
 * <date-term> ::= before:<string> | after:<string>
 */
class TermParser
{
    public function __construct(private readonly StringParser $stringParser)
    {
    }

    /**
     * @throws Exception
     */
    public function parse(StringReader $string): TermInterface
    {
        $string->skipWhitespace();

        if (strcasecmp('before:', $string->peek(7)) === 0) {
            $string->next(7);
            return new DateBeforeTerm(new DateTimeImmutable($this->stringParser->parse($string)));
        }

        if (strcasecmp('after:', $string->peek(6)) === 0) {
            $string->next(6);
            return new DateAfterTerm(new DateTimeImmutable($this->stringParser->parse($string)));
        }

        if (strcasecmp('exclude:', $string->peek(8)) === 0) {
            $string->next(8);
            return new WordTerm($this->stringParser->parse($string), WordTerm::TYPE_EXCLUDE);
        }

        return new WordTerm($this->stringParser->parse($string), WordTerm::TYPE_INCLUDE);
    }
}
