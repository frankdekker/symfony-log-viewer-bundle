<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\File;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\FinderFactory;
use FD\LogViewer\Service\Folder\LogFolderFactory;
use Traversable;

class LogFileService
{
    /**
     * @param Traversable<int, LogFilesConfig> $logFileConfigs
     */
    public function __construct(
        private readonly Traversable $logFileConfigs,
        private readonly FinderFactory $folderService,
        private readonly LogFolderFactory $logFolderFactory
    ) {
    }

    /**
     * @return LogFolderCollection[]
     */
    public function getFilesAndFolders(): array
    {
        $collections = [];
        foreach ($this->logFileConfigs as $config) {
            $finder        = $this->folderService->createForConfig($config->finderConfig);
            $collections[] = $this->logFolderFactory->createFromFiles($config, $finder);
        }

        return $collections;
    }

    /**
     * @param string[] $fileIdentifiers
     *
     * @return array<string, LogFile>
     */
    public function findFileByIdentifiers(array $fileIdentifiers): array
    {
        $files       = [];
        $collections = $this->getFilesAndFolders();
        foreach ($fileIdentifiers as $fileIdentifier) {
            foreach ($collections as $collection) {
                $file = $collection->firstFile(static fn(LogFile $file) => $file->identifier === $fileIdentifier);
                if ($file !== null) {
                    $files[$fileIdentifier] = $file;
                    continue 2;
                }
            }
        }

        return $files;
    }

    public function findFileByIdentifier(string $fileIdentifier): ?LogFile
    {
        $collections = $this->getFilesAndFolders();
        foreach ($collections as $collection) {
            $file = $collection->firstFile(static fn(LogFile $file) => $file->identifier === $fileIdentifier);
            if ($file !== null) {
                return $file;
            }
        }

        return null;
    }

    public function findFolderByIdentifier(string $folderIdentifier): ?LogFolder
    {
        $collections = $this->getFilesAndFolders();
        foreach ($collections as $collection) {
            $folder = $collection->first(static fn(LogFolder $folder) => $folder->identifier === $folderIdentifier);
            if ($folder !== null) {
                return $folder;
            }
        }

        return null;
    }
}
