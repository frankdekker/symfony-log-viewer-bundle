<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Service\FinderFactory;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderFactory;
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
