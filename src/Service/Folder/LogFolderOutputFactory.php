<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Service\Folder;

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
                rtrim($folders->config->name . '/' . $path, '/'),
                '', // TODO $this->urlGenerator->generate(DownloadFolderController::class, ['folderIdentifier' => $folder->getIdentifier()]),
                $folders->config->downloadable && extension_loaded('zip'),
                $folder->getLatestTimestamp(),
                array_map(fn($file) => $this->createFromFile($file, $folders->config->downloadable), $folder->getFiles()),
            );
        }

        return $result;
    }

    public function createFromFile(LogFile $file, bool $downloadable): LogFileOutput
    {
        return new LogFileOutput(
            $file->identifier,
            basename($file->path),
            Utils::bytesForHumans($file->size),
            '', // TODO $this->urlGenerator->generate(DownloadFileController::class, ['fileIdentifier' => $file->getIdentifier()]),
            $file->createTimestamp,
            $file->updateTimestamp,
            $downloadable,
        );
    }
}
