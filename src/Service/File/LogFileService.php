<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Service\FinderService;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderFactory;
use Traversable;

class LogFileService
{
    /**
     * @param Traversable<int, LogFilesConfig> $logFileConfigs
     */
    public function __construct(
        private readonly Traversable $logFileConfigs,
        private readonly FinderService $folderService,
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
            $finder        = $this->folderService->findFiles($config->finderConfig);
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
}
