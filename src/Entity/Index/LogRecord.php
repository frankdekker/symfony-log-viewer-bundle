<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

class LogRecord
{
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
     * @return array<string, int|string>
     */
    public function __serialize(): array
    {
        return [
            'date'     => $this->date,
            'severity' => $this->severity,
            'channel'  => $this->channel,
            'message'  => $this->message,
            'context'  => $this->context,
            'extra'    => $this->extra,
        ];
    }

    /**
     * @param array<string, int|string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->date     = (int)$data['date'];
        $this->severity = (string)$data['severity'];
        $this->channel  = (string)$data['channel'];
        $this->message  = (string)$data['message'];
        $this->context  = $data['context'];
        $this->extra    = $data['extra'];
    }
}
