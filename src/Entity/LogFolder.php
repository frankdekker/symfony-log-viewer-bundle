<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity;

class LogFolder
{
    /** @var LogFile[] */
    private array $files;

    public function __construct(
        public readonly string $identifier,
        public readonly string $path,
        public readonly string $relativePath,
        private int $earliestTimestamp,
        private int $latestTimestamp,
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

    public function getEarliestTimestamp(): int
    {
        return $this->earliestTimestamp;
    }

    public function updateEarliestTimestamp(int $earliestTimestamp): self
    {
        $this->earliestTimestamp = min($this->earliestTimestamp, $earliestTimestamp);

        return $this;
    }

    public function getLatestTimestamp(): int
    {
        return $this->latestTimestamp;
    }

    public function updateLatestTimestamp(int $latestTimestamp): self
    {
        $this->latestTimestamp = max($this->latestTimestamp, $latestTimestamp);

        return $this;
    }

    public function addFile(LogFile $file): self
    {
        $file->setFolder($this);
        $this->files[] = $file;

        return $this;
    }

    /**
     * @return LogFile[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
