<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use FD\LogViewer\Entity\IdentifierAwareInterface;
use JsonSerializable;
use Psr\Log\LogLevel;

class LogRecord implements IdentifierAwareInterface
{
    /**
     * @param string|array<int|string, mixed> $context
     * @param string|array<int|string, mixed> $extra
     */
    public function __construct(
        private string $identifier,
        public int $date,
        public string $severity,
        public string $channel,
        public string $message,
        public string|array $context,
        public string|array $extra
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
