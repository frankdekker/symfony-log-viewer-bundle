<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

class LogFile
{
    private ?LogFolder $folder;

    public function __construct(
        private string $identifier,
        private string $path,
        private string $relativePath,
        private int $size,
        private int $createTimestamp,
        private int $updateTimestamp
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setFolder(LogFolder $folder): void
    {
        $this->folder = $folder;
    }

    public function getFolder(): ?LogFolder
    {
        return $this->folder;
    }

    public function getCreateTimestamp(): int
    {
        return $this->createTimestamp;
    }

    public function getUpdateTimestamp(): int
    {
        return $this->updateTimestamp;
    }
}
