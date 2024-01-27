<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Expression;

class ChannelTerm implements TermInterface
{
    /**
     * @codeCoverageIgnore Simple DTO
     *
     * @param string[] $channels
     */
    public function __construct(public readonly array $channels)
    {
    }
}
