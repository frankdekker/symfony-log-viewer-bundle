<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;

class LogFileService
{
    public function __construct(private readonly FinderService $folderService, private readonly LogFolderFactory $logFolderFactory)
    {
    }

    public function getFilesAndFolders(): LogFolderCollection
    {
        return $this->logFolderFactory->createFromFiles($this->folderService->findFiles());
    }

    public function findFileByIdentifier(string $fileIdentifier): ?LogFile
    {
        return $this->getFilesAndFolders()->firstFile(static fn($file) => $file->getIdentifier() === $fileIdentifier);
    }

    public function findFolderByIdentifier(string $folderIdentifier): ?LogFolder
    {
        return $this->getFilesAndFolders()->first(static fn($folder) => $folder->getIdentifier() === $folderIdentifier);
    }
}
