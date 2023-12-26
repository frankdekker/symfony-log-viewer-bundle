<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

use FD\SymfonyLogViewerBundle\Service\LogLevelOutputFactory;
use JsonSerializable;

class LogRecord implements JsonSerializable
{
    /**
     * @param string|array<int|string, mixed> $context
     * @param string|array<int|string, mixed> $extra
     */
    public function __construct(
        public int $date,
        public string $severity,
        public string $channel,
        public string $message,
        public string|array $context,
        public string|array $extra
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'datetime'    => date('Y-m-d H:i:s', $this->date),
            'level_name'  => ucfirst($this->severity),
            'level_class' => LogLevelOutputFactory::LEVEL_CLASSES[$this->severity] ?? 'text-info',
            'channel'     => $this->channel,
            'text'        => $this->message,
            'context'     => $this->context,
            'extra'       => $this->extra,
        ];
    }
}
