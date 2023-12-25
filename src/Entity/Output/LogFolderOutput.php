<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Output;

use JsonSerializable;

class LogFolderOutput implements JsonSerializable
{
    /**
     * @param LogFileOutput[] $files
     */
    public function __construct(
        public readonly string $identifier,
        public readonly string $path,
        public readonly string $downloadUrl,
        public readonly bool $canDownload,
        public readonly int $latestTimestamp,
        public array $files,
    ) {
    }

    /**
     * @inheritdoc
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier'   => $this->identifier,
            'path'         => $this->path,
            'download_url' => $this->downloadUrl,
            'files'        => $this->files,
            'can_download' => $this->canDownload
        ];
    }
}
