<?php
declare(strict_types=1);

namespace FD\LogViewer\Entity;

use FD\LogViewer\Entity\Config\LogFilesConfig;

class LogFolderCollection
{
    /** @var array<string, LogFolder> */
    private array $folders = [];

    public function __construct(public readonly LogFilesConfig $config)
    {
    }

    /**
     * @param (callable(LogFolder):bool)|null $callback
     */
    public function first(callable|null $callback = null): ?LogFolder
    {
        foreach ($this->folders as $folder) {
            if ($callback === null || $callback($folder)) {
                return $folder;
            }
        }

        return null;
    }

    /**
     * @param (callable(LogFile):bool)|null $callback
     */
    public function firstFile(callable|null $callback = null): ?LogFile
    {
        foreach ($this->folders as $folder) {
            foreach ($folder->getFiles() as $file) {
                if ($callback === null || $callback($file)) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     * @param callable():LogFolder $callback
     */
    public function getOrAdd(string $path, callable $callback): LogFolder
    {
        return $this->folders[$path] ?? $this->folders[$path] = $callback();
    }

    /**
     * @return array<int, LogFolder>
     */
    public function toArray(): array
    {
        return array_values($this->folders);
    }
}
