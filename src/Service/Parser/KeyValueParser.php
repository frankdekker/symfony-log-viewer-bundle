<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Parser;

use FD\LogViewer\Entity\Expression\KeyValueTerm;
use FD\LogViewer\Reader\String\StringReader;

class KeyValueParser
{
    public function __construct(private readonly StringParser $stringParser)
    {
    }

    /**
     * @phpstan-param KeyValueTerm::TYPE_* $type
     */
    public function parse(string $type, StringReader $string): KeyValueTerm
    {
        $value = $this->stringParser->parse($string, ['.', '=']);
        if ($string->eol()) {
            return new KeyValueTerm($type, null, $value);
        }

        $keys = [];
        if ($string->char() === '.') {
            $keys[] = $value;

            while ($string->eol() === false || $string->char() === '=') {
                $string->next();
                $keys[] = $this->stringParser->parse($string, ['.', '=']);
            }

            if ($string->eol()) {
                return new KeyValueTerm($type, null, implode('.', $keys));
            }
        }

        // skip '='
        $string->next();

        return new KeyValueTerm($type, $keys, $this->stringParser->parse($string));
    }
}
