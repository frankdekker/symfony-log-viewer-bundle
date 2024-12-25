<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Entity\Expression\ChannelTerm;
use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Entity\Expression\SeverityTerm;
use FD\LogViewer\Entity\Expression\TermInterface;
use FD\LogViewer\Entity\Expression\WordTerm;
use FD\LogViewer\Reader\String\StringReader;

/**
 * BNF
 * <term> ::= <exclude-term> | <string>
 * <exclude-term> ::= exclude:<string>
 */
class TermParser
{
    public function __construct(private readonly StringParser $stringParser, private readonly KeyValueParser $keyValueParser)
    {
    }

    public function parse(StringReader $string): TermInterface
    {
        $string->skipWhitespace();

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
