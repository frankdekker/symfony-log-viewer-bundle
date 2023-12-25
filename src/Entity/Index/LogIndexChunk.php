<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Entity\Index;

class LogIndexChunk
{
    /** @var array<string, mixed> */
    private array $data;
    private int   $index;
    private int   $earliestTimestamp;
    private int   $latestTimestamp;
    private int   $size;
    /** @var array<string, int> */
    private array $levelCounts = [];

    public function addToIndex(int $logIndex, int $filePosition, int $timestamp, string $severity): void
    {
        $this->levelCounts[$severity] ??= 0;
        ++$this->levelCounts[$severity];
        ++$this->size;

        $this->data[$timestamp][$severity][$logIndex] = $filePosition;
        $this->earliestTimestamp                      = min($this->earliestTimestamp ?? $timestamp, $timestamp);
        $this->latestTimestamp                        = max($this->latestTimestamp ?? $timestamp, $timestamp);
    }

    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        return [
            'data'              => $this->data,
            'index'             => $this->index,
            'size'              => $this->size,
            'levelCounts'       => $this->levelCounts,
            'earliestTimestamp' => $this->earliestTimestamp,
            'latestTimestamp'   => $this->latestTimestamp,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $this->data              = $data['data'];
        $this->index             = $data['index'];
        $this->size              = $data['size'];
        $this->levelCounts       = $data['levelCounts'];
        $this->earliestTimestamp = $data['earliestTimestamp'];
        $this->latestTimestamp   = $data['latestTimestamp'];
    }
}
