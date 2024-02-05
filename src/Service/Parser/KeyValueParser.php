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

        $keys = [$value];
        if ($string->char() === '.') {
            while ($string->eol() === false && $string->char() === '.') {
                $string->next();
                $keys[] = $this->stringParser->parse($string, ['.', '=']);
            }

            if ($string->char() !== '=') {
                return new KeyValueTerm($type, null, implode('.', $keys));
            }
        }

        if ($string->char() === '=') {
            // skip =
            $string->next();

            return new KeyValueTerm($type, $keys, $this->stringParser->parse($string));
        }

        return new KeyValueTerm($type, null, $value);
    }
}
