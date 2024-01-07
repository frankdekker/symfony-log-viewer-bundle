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
        public readonly LogFolderCollection $collection
    ) {
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
