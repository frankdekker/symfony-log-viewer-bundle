<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use FD\LogViewer\Entity\IdentifierAwareInterface;

class LogRecord implements IdentifierAwareInterface
{
    private bool $contextLine = false;

    /**
     * @param string|array<int|string, mixed> $context
     * @param string|array<int|string, mixed> $extra
     */
    public function __construct(
        private readonly string $identifier,
        public readonly string $originalRecord,
        public readonly int $date,
        public readonly string $severity,
        public readonly string $channel,
        public readonly string $message,
        public readonly string|array $context,
        public readonly string|array $extra
    ) {
    }

    public function isContextLine(): bool
    {
        return $this->contextLine;
    }

    public function setContextLine(bool $contextLine): self
    {
        $this->contextLine = $contextLine;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
