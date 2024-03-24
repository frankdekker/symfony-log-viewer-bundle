<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Index;

use FD\LogViewer\Entity\IdentifierAwareInterface;
use JsonSerializable;
use Psr\Log\LogLevel;

class LogRecord implements JsonSerializable, IdentifierAwareInterface
{
    // bootstrap 5 text colors
    public const LEVEL_CLASSES = [
        LogLevel::DEBUG     => 'text-info',
        LogLevel::INFO      => 'text-info',
        LogLevel::NOTICE    => 'text-info',
        LogLevel::WARNING   => 'text-warning',
        LogLevel::ERROR     => 'text-danger',
        LogLevel::ALERT     => 'text-danger',
        LogLevel::CRITICAL  => 'text-danger',
        LogLevel::EMERGENCY => 'text-danger',
    ];

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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'datetime'    => date('Y-m-d H:i:s', $this->date),
            'level_name'  => ucfirst($this->severity),
            'level_class' => self::LEVEL_CLASSES[$this->severity] ?? 'text-info',
            'channel'     => $this->channel,
            'text'        => $this->message,
            'context'     => $this->context,
            'extra'       => $this->extra,
        ];
    }
}
