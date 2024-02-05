<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class KeyValueTerm implements TermInterface
{
    public const TYPE_CONTEXT = 'context';
    public const TYPE_EXTRA   = 'extra';

    /**
     * @codeCoverageIgnore Simple DTO
     * @param self::TYPE_*  $type
     * @param string[]|null $keys
     */
    public function __construct(public readonly string $type, public readonly ?array $keys, public readonly string $value)
    {
    }
}
