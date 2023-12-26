<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFileOutput;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;
use FD\SymfonyLogViewerBundle\Util\Utils;

class LogFolderOutputFactory
{
    /**
     * @return LogFolderOutput[]
     */
    public function createFromFolders(LogFolderCollection $folders): array
    {
        $result = [];

        foreach ($folders->toArray() as $folder) {
            $path = $folder->getRelativePath();

            $result[] = new LogFolderOutput(
                $folder->getIdentifier(),
                $path === '' ? 'Root' : $path, // TODO show name of root folder
                '', // TODO $this->urlGenerator->generate(DownloadFolderController::class, ['folderIdentifier' => $folder->getIdentifier()]),
                extension_loaded('zip'), // TODO add grants
                $folder->getLatestTimestamp(),
                array_map(fn($file) => $this->createFromFile($file), $folder->getFiles()),
            );
        }

        return $result;
    }

    public function createFromFile(LogFile $file): LogFileOutput
    {
        $folder = $file->getFolder();
        assert($folder !== null);

        return new LogFileOutput(
            $file->identifier,
            basename($file->path),
            Utils::bytesForHumans($file->size),
            '', // TODO $this->urlGenerator->generate(DownloadFileController::class, ['fileIdentifier' => $file->getIdentifier()]),
            $file->createTimestamp,
            $file->updateTimestamp,
            true, // TODO add grants
        );
    }
}
