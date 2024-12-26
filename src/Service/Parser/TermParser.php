<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use DateTime;
use DateTimeZone;
use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\DateAfterTerm;
use FD\LogViewer\Entity\Expression\DateBeforeTerm;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Reader\String\StringReader;

/**
 * BNF
 * <term> ::= <date-term> | <exclude-term> | <string>
 * <exclude-term> ::= exclude:<string>
 * <date-term> ::= before:<string> | after:<string>
 */
class TermParser
{
    public function __construct(
        private readonly StringParser $stringParser,
        private readonly DateParser $dateParser,
        private readonly KeyValueParser $keyValueParser
    ) {
    }

    /**
     * @throws InvalidDateTimeException
     */
    public function parse(StringReader $string, DateTimeZone $timeZone): TermInterface
    {
        $string->skipWhitespace();

        if ($string->read('before:') || $string->read('b:')) {
            return new DateBeforeTerm($this->dateParser->toDateTimeImmutable($this->stringParser->parse($string), $timeZone));
        }

        if ($string->read('after:') || $string->read('a:')) {
            return new DateAfterTerm($this->dateParser->toDateTimeImmutable($this->stringParser->parse($string), $timeZone));
        }

        if ($string->read('severity:') || $string->read('s:')) {
            return new SeverityTerm(array_map('trim', explode('|', $this->stringParser->parse($string))));
        }

        if ($string->read('channel:') || $string->read('c:')) {
            return new ChannelTerm(array_map('trim', explode('|', $this->stringParser->parse($string))));
        }

        if ($string->read('exclude:') || $string->read('-:')) {
            return new WordTerm($this->stringParser->parse($string), WordTerm::TYPE_EXCLUDE);
        }

        if ($string->read('context:')) {
            return $this->keyValueParser->parse(KeyValueTerm::TYPE_CONTEXT, $string);
        }

        if ($string->read('extra:')) {
            return $this->keyValueParser->parse(KeyValueTerm::TYPE_EXTRA, $string);
        }

        return new WordTerm($this->stringParser->parse($string), WordTerm::TYPE_INCLUDE);
    }
}
