<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Parser;

class WordTerm implements TermInterface
{
    public const TYPE_INCLUDE = 'include';
    public const TYPE_EXCLUDE = 'exclude';

    /**
     * @codeCoverageIgnore Simple DTO
     * @phpstan-param self::TYPE_* $type
     */
    public function __construct(public readonly string $string, public readonly string $type)
    {
    }
}
