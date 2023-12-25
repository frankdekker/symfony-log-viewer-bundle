<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

use RuntimeException;

class LogIndex
{
    private ?int $earliestTimestamp = null;
    private ?int $latestTimestamp   = null;

    /** @var LogRecord[] */
    private array $lines = [];

    /** @var array<string, int> */
    private array $levelCounts = [];

    private ?Paginator $paginator = null;

    public function __construct(private int $iNode, private int $position, private int $fileSize)
    {
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginator;
    }

    public function setPaginator(Paginator $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }

    public function addLine(LogRecord $line): void
    {
        $this->levelCounts[$line->severity] ??= 0;
        ++$this->levelCounts[$line->severity];

        $this->earliestTimestamp = min($this->earliestTimestamp ?? $line->date, $line->date);
        $this->latestTimestamp   = max($this->latestTimestamp ?? $line->date, $line->date);

        $this->lines[] = $line;
    }

    public function getINode(): int
    {
        return $this->iNode;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function getEarliestTimestamp(): int
    {
        return $this->earliestTimestamp ?? throw new RuntimeException('Earliest timestamp is not set.');
    }

    public function getLatestTimestamp(): int
    {
        return $this->latestTimestamp ?? throw new RuntimeException('Latest timestamp is not set.');
    }

    /**
     * @return array<string, int>
     */
    public function getLevelCounts(): array
    {
        return $this->levelCounts;
    }

    /**
     * @return LogRecord[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        return [
            'earliestTimestamp' => $this->earliestTimestamp,
            'latestTimestamp'   => $this->latestTimestamp,
            'lines'             => $this->lines,
            'levelCounts'       => $this->levelCounts
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $this->earliestTimestamp = $data['earliestTimestamp'];
        $this->latestTimestamp   = $data['latestTimestamp'];
        $this->lines             = $data['lines'];
        $this->levelCounts       = $data['levelCounts'];
    }
}
