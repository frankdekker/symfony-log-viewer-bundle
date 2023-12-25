<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Output;

use JsonSerializable;

class LogFileOutput implements JsonSerializable
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $name,
        public readonly string $sizeFormatted,
        public readonly string $downloadUrl,
        public readonly int $earliestTimestamp,
        public readonly int $latestTimestamp,
        public readonly bool $canDownload
    ) {
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier'     => $this->identifier,
            'name'           => $this->name,
            'size_formatted' => $this->sizeFormatted,
            'download_url'   => $this->downloadUrl,
            'can_download'   => $this->canDownload,
        ];
    }
}
