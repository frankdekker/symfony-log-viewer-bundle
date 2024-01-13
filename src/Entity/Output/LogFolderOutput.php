<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity\Output;

use JsonSerializable;

class LogFolderOutput implements JsonSerializable
{
    /**
     * @param LogFileOutput[] $files
     */
    public function __construct(
        public readonly string $identifier,
        public readonly string $path,
        public readonly bool $canDownload,
        public readonly bool $canDelete,
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
            'files'        => $this->files,
            'can_download' => $this->canDownload,
            'can_delete'   => $this->canDelete
        ];
    }
}
