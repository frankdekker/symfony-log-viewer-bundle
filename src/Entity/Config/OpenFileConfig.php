<?php

declare(strict_types=1);

namespace FD\LogViewer\Entity\Config;

class OpenFileConfig
{
    public const ORDER_NEWEST = 'newest';
    public const ORDER_OLDEST = 'oldest';

    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param self::ORDER_NEWEST|self::ORDER_OLDEST $order
     */
    public function __construct(public readonly string $pattern, public readonly string $order)
    {
    }
}
