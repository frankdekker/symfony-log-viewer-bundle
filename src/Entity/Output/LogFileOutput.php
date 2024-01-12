<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use JsonSerializable;

class LogFileOutput implements JsonSerializable
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $name,
        public readonly string $sizeFormatted,
        public readonly int $earliestTimestamp,
        public readonly int $latestTimestamp,
        public readonly bool $canDownload,
        public readonly bool $canDelete
    ) {
    }

    /**
     * @return array<string, string|int|bool>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier'     => $this->identifier,
            'name'           => $this->name,
            'size_formatted' => $this->sizeFormatted,
            'can_download'   => $this->canDownload,
            'can_delete'     => $this->canDelete
        ];
    }
}
